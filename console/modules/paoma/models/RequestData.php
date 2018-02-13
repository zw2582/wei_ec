<?php
namespace console\modules\paoma\models;

use console\modules\paoma\swoole\PaomaHandler;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUUid;
use paoma\models\Room;
use yii\base\Model;

/**
 * 所有请求数据
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午3:10:27
 */
class RequestData extends Model{
    
    /**
     * 请求类型
     * auth_request 认证请求
     * auth_confirm 确认认证
     * play 摇晃手机
     * start 开始比赛
     * exit 退出房间
     * enter 进入房间
     */
    public $action;
    
    //跑马用户唯一认证
    public $uuid;
    
    //用户id
    public $uid;
    
    //房间号
    public $room_no;
    
    //摇晃的次数（每次请求）
    public $count;
    
    /**
     * @var PaomaHandler websocket请求处理器，保存了fd资源表
     */
    private $handler;
    
    /**
     * @var \swoole_server
     */
    private $serv;
    
    public function rules() {
        return [
            [['action','uuid'], 'required'],
            [['action', 'uuid'], 'string'],
            [['count','uid'], 'integer'],
            ['room_no', 'safe']
        ];
    }
    
    public function sendFail($fd, $message, $data='') {
        if (!$this->serv->exist($fd)) {
            return;
        }
        $this->serv->push($fd, json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public function sendSucc($fd, $data, $message ='') {
        if (!$this->serv->exist($fd)) {
            return;
        }
        $this->serv->push($fd, json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    /**
     * 执行业务逻辑
     * @param PaomaHandler $handler
     * wei.w.zhou@integle.com
     * 2018年2月7日下午3:46:37
     */
    public function process(PaomaHandler $handler, \swoole_server $serv) {
        $this->handler = $handler;
        $this->serv = $serv;
	echo "process".$this->action."\n";
        switch ($this->action) {
            case 'auth_request':
                return $this->actionAuthRequest();
            case 'auth_confirm':
                return $this->actionAuthConfirm();
            case 'play':
                return $this->actionPlay(); //结束之后，isactive:2=>3
            case 'start':
                return $this->actionStart();  //开始启动,isactive:1=>2
            case 'exit':
                return $this->exitRoom();
            case 'enter':
                return $this->enterRoom();
            case 'prepare':
                return $this->actionPrepare();  //准备：isactive:3=>1
            default:
                return;
        }
    }
    
    /**
     * 认证请求（主要用于web端需要靠微信来获取认证信息）
     * 存储用户的认证请求，有效期一个小时，等待客户端确认请求
     * wei.w.zhou@integle.com
     * 2018年2月7日下午3:52:45
     */
    public function actionAuthRequest() {
        PaomaAuth::add($this->uuid);
        
        $phonefd = $this->handler->phoneFdTable->get($this->uuid, 'fd');
        if ($phonefd && $this->serv->exist($phonefd)) {
            $this->sendSucc($phonefd, [
                'action'=>'auth_request'
            ]);
        }
    }
    
    /**
     * 确认认证请求
     * 
     * wei.w.zhou@integle.com
     * 2018年2月8日上午9:21:19
     */
    public function actionAuthConfirm(){
        $phonefd = $this->handler->phoneFdTable->get($this->uuid, 'fd');
        //获取待审核的认证
        $wait = PaomaAuth::get($this->uuid);
        if (empty($wait)) {
            \Yii::error(sprintf("uuid:%s 认证已过期，请刷新二维码重新发起认证", $this->uuid), "auth_confirm");
            return $this->sendFail($phonefd, '认证已过期，请刷新二维码重新发起认证');
        }
        //判断返回的fd是否还存在且有效
        $webfd = $this->handler->webFdTable->get($this->uuid, 'fd');
        if (!($fd && $this->serv->exist($fd))) {
            \Yii::error(sprintf("uuid:%s 服务端的fd链接已断开，无法返回认证信息", $this->uuid), 'auth_confirm');
            return $this->sendFail($phonefd, '您的网页端已离开房间，请进入房间');
        }
        //返回认证信息给web端
        return $this->sendSucc($webfd, json_encode([
            'action'=>'auth_confirm',
            'uuid'=>$this->uuid,
            'uid'=>$this->uid
        ]));
    }
    
    /**
     * 摇晃手机
     * 
     * wei.w.zhou@integle.com
     * 2018年2月8日上午9:55:01
     */
    public function actionPlay() {
        $room = Room::findOne($this->room_no);
        $fd = $this->handler->phoneFdTable->get($this->uuid);
        //校验房间数据
        if (empty($room) || $room['isactive'] != 2) {
            \Yii::error('房间不存在，或房间还未开赛', 'play');
            return $this->sendFail($fd, '房间不存在，或房间还未开赛');
        }
        //校验用户数据
        if (!PaomaRoomUsers::exist($this->room_no, $this->uuid)) {
            \Yii::error('不是该房间成员', 'play');
            return $this->sendFail($fd, '您不是该房间成员');
        }
        if (!PaomaUUid::getUidByUUid($this->uuid)) {
            \Yii::error('还没有认证成功', 'play');
            return $this->sendFail($fd, '您还没有认证成功');
        }
        //增加分数
        if (!PaomaRoomScore::add($this->room_no, $this->uuid, $this->count)) {
            //时间到，修改房间状态
            Room::updateStatus($this->room_no, 3);
            return $this->sendSucc($fd, [
                'action'=>'comple'
            ]);
        }
        //返回跑马结果给所有用户,只需要一个task进程执行就好了，每秒返回一次
        if (PaomaReport::set($this->room_no, $this->serv->worker_id)) {
            $this->serv->tick(1000, function($id){
                if (!PaomaRoomScore::status($this->room_no)) {
                    \Yii::info("roomno:{$this->room_no}跑马比赛已结束，清除汇报定时器", 'play');
                    $this->serv->clearTimer($id);
                }
                //获取房间内的所有用户
                $uuids = PaomaRoomUsers::members($this->room_no);
                foreach ($uuids as $uuid) {
                    //获取webfd
                    //$webuserfd = $this->handler->webFdTable->get($uuid, 'fd');
                    $phoneuserfd = $this->handler->phoneFdTable->get($uuid, 'fd');
                    //计算显示10个跑马用户
                    if ($room['uuid'] == $uuid) {
                        //如果是房主，则返回前十名的用户
                        $data = PaomaRoomScore::listTop($this->room_no);
                    } else {
                        $data = PaomaRoomScore::listByUUid($this->room_no, $uuid);
                    }
                    $this->sendSucc($phoneuserfd, [
                        'action'=>'play',
                        'data'=>$data,
                    ]);
                }
            });
        }
    }
    
    /**
     * 开始命令
     * 
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:49:21
     */
    public function actionStart() {
        $fd = $this->handler->phoneFdTable->get($this->uuid);
        //检查房间是否存在,且房主已认证
        $room = Room::findOne($this->room_no);
        if (empty($room) || $room['isactive'] == 0) {
            \Yii::info(sprintf("roomno:%s 房间不存在，或房主为认证", $this->room_no), 'start');
            return $this->sendFail($fd, '房间不存在，或房主为认证');
        }
        //判断用户权限
        if ($this->uuid != $room['uuid']) {
            \Yii::info("您不是房主,没有开始权限", __METHOD__);
            return $this->sendFail($fd, "您不是房主，没有开始权限");
        }
        //修改房间比赛状态，建立比赛结束点
        Room::updateStatus($this->room_no, 2);
        PaomaRoomScore::begin($this->room_no);
        
        //通知房间内所有用户
        $uuids = PaomaRoomUsers::members($this->room_no);
        foreach ($uuids as $uuid) {
            //获取webfd
            $phoneFd = $this->handler->phoneFdTable->get($uuid, 'fd');
            $this->sendSucc($phoneFd, ['action'=>'start']);
            
        }
        foreach ($uuids as $uuid) {
            //获取webfd
            $webuserfd = $this->handler->webFdTable->get($uuid, 'fd');
            $this->sendSucc($webuserfd, ['action'=>'start']);
        }
    }
    
    /**
     * 再次准备
     * 
     * wei.w.zhou@integle.com
     * 2018年2月9日上午9:17:06
     */
    public function actionPrepare() {
        $fd = $this->handler->phoneFdTable->get($this->uuid);
        //检查房间是否存在,且房主已认证
        $room = Room::findOne($this->room_no);
        if (empty($room) || $room['isactive'] == 0) {
            \Yii::info(sprintf("roomno:%s 房间不存在，或房主为认证", $this->room_no), 'start');
            return $this->sendFail($fd, '房间不存在，或房主为认证');
        }
        //判断用户权限
        if ($this->uuid != $room['uuid']) {
            \Yii::info("您不是房主,没有开始权限", __METHOD__);
            return $this->sendFail($fd, "您不是房主，没有开始权限");
        }
        //修改房间比赛状态
        Room::updateStatus($this->room_no, 1);
        //清空上一次比赛结果
        PaomaRoomScore::clear($this->room_no);
        
        //通知房间内所有用户
        $uuids = PaomaRoomUsers::members($this->room_no);
        foreach ($uuids as $uuid) {
            //获取webfd
            $phoneFd = $this->handler->phoneFdTable->get($uuid, 'fd');
            $this->sendSucc($phoneFd, ['action'=>'prepare']);
        }
        foreach ($uuids as $uuid) {
            //获取webfd
            $webuserfd = $this->handler->webFdTable->get($uuid, 'fd');
            $this->sendSucc($webuserfd, ['action'=>'prepare']);
        }
    }
    
    /**
     * 进入房间
     * 1.如果房间人数不超过10人，且房间不在进行中，则返回所有用户新增用户信息，如果超过则只返回所有用户当前房间用户总数，返回当前用户前10人信息
     * 
     * wei.w.zhou@integle.com
     * 2018年2月12日上午10:50:17
     */
    public function actionEnterRoom() {
        $currentFd = $this->handler->phoneFdTable->get($this->uuid, 'fd');
        $count = PaomaRoomUsers::count($this->room_no);
        $room = Room::findOne($this->room_no);
        if (empty($room)) {
            return $this->sendFail($currentFd, '房间'.$this->room_no.' 不存在');
        }
        if ($room['isactive'] == 2) {
            $data = PaomaRoomUsers::members($this->room_no);
            $this->sendSucc($currentFd, ['action'=>'join', 'data'=>$data]);
        } elseif ($count < 10) {
            //通知房间内所有用户
            $uuids = PaomaRoomUsers::members($this->room_no);
            foreach ($uuids as $uuid) {
                //获取fd
                $phoneFd = $this->handler->phoneFdTable->get($uuid, 'fd');
                $data = PaomaRoomUsers::members($this->room_no);
                $this->sendSucc($phoneFd, ['action'=>'join', 'data'=>$data]);
            }
        }
        //返回所有用户人员总数
        $uuids = PaomaRoomUsers::members($this->room_no);
        foreach ($uuids as $uuid) {
            //获取phoneFd
            $phoneFd = $this->handler->phoneFdTable->get($uuid, 'fd');
            $this->sendSucc($phoneFd, ['action'=>'member_count', 'data'=>$count]);
        }
    }
    
}

