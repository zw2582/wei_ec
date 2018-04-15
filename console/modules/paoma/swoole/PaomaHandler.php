<?php
namespace console\modules\paoma\swoole;

use console\modules\paoma\models\Utils;
use swoole_http_request;
use swoole_websocket_server;
use paoma\models\PaomaRoomUsers;
use console\modules\paoma\models\EnterRoomForm;
use console\modules\paoma\models\AuthRequestForm;
use console\modules\paoma\models\AuthConfirmForm;
use console\modules\paoma\models\OutRoomForm;
use console\modules\paoma\models\PrepareForm;
use console\modules\paoma\models\StartForm;
use console\modules\paoma\models\PlayForm;
use console\modules\paoma\tasks\SendResultTask;
use console\modules\paoma\tasks\SendTask;
use paoma\models\PaomaRoomFd;

/**
 * 跑马处理
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午2:36:01
 */
class PaomaHandler{
    
    public $webFdTable;
    
    public $phoneFdTable;
    
    public $authFdTable;
    
    public $serv;
    
    private static $self;
    
    public static function getInstance(\swoole_server $serv = NULL) {
        if (!self::$self instanceof PaomaHandler) {
            if (empty($serv)) {
                throw new \Exception('实例化PaomaHandler缺少serv');
            }
            self::$self = new self($serv);
        } 
        return self::$self;
    }
    
    private final function __construct(\swoole_server $serv) {
        
        $this->serv = $serv;
	   echo "创建fd句柄表\n";
        /**
         * 创建web端fd句柄表
         * key:uid
         */
        $this->webFdTable = new \swoole_table(1000);
        $this->webFdTable->column('fd', \swoole_table::TYPE_INT);
        $this->webFdTable->create();
        
        /**
         * 创建手机端fd句柄表
         * key:uid
         */
        $this->phoneFdTable = new \swoole_table(1000);
        $this->phoneFdTable->column('fd', \swoole_table::TYPE_INT);
        $this->phoneFdTable->create();
        
        /**
         * 创建认证fd句柄表
         * key:uuid
         */
        $this->authFdTable = new \swoole_table(1000);
        $this->authFdTable->column('fd', \swoole_table::TYPE_INT);
        $this->authFdTable->create();
    }
    
    public function onClose(\swoole_server $server, $fd, $reactorId) {
        //判断$fd所在的手机端uuid是否存在房主房间，如果存在，则提醒用户房主已离开房间
        \Yii::info(sprintf("reactorId:%d close a fd:%d", $reactorId, $fd), 'onClose');
    }

    /**
     * 连接到websocket时，根据source保存uuid和fd
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onOpen()
     */
    public function onOpen(swoole_websocket_server $svr, swoole_http_request $req) {
	    YII_DEBUG && print("open\n");
        \Yii::info('连接到websocket时，根据source保存uuid和fd', 'paomahandler');
        //保存uuid和fd
        $get = $req->get;
        $fd = $req->fd;
        
        if (isset($get['uid'])) {
            $uid = $get['uid'];
            $source = $req->get['source'];
            if (empty($source) || !in_array($source, ['web', 'phone'])) {
                return Utils::sendFail($svr, $fd, '缺少必须参数source');
            }
            //保存uid和fd
            $table = $source == 'web' ? $this->webFdTable : $this->phoneFdTable;
            $oldfd = $table->get($uid, 'fd');
            if ($oldfd !== false) {
                YII_DEBUG && print("close oldfd:$oldfd\n");
                $svr->stop($oldfd, true);
            }
            $table->set($uid, ['fd'=>$fd]);
        }
        
        return Utils::sendSucc($svr, $fd, '连接成功');
    }

    /**
     * 当获取到消息时，直接转发给task
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onMessage()
     */
    public function onMessage(\swoole_server $server, \swoole_websocket_frame $frame) {
        $data = json_decode($frame->data, true);
        if (empty($data['action'])) {
            YII_DEBUG && print("message:".$frame->data."\n");
        } else {
            YII_DEBUG && print("message:".$data['action']."\n");
            switch ($data['action']) {
                case 'auth_request':
                    $model = new AuthRequestForm();
                    $model->attributes = $data;
                    if (!$model->save($server, $frame, $this->authFdTable)) {
                        printf("认证请求失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("认证请求失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::sendSucc($server, $frame->fd, null, '认证请求成功');
                    }
                    break;
                case 'auth_confirm':
                    $model = new AuthConfirmForm();
                    $model->attributes = $data;
                    if (!$model->save($server, $this->webFdTable, $this->authFdTable)) {
                        printf("认证确认失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("认证确认失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::sendSucc($server, $frame->fd, null, '认证确认成功');
                    }
                    break;
                case 'enter':
                    //加入房间
                    $enter = new EnterRoomForm();
                    $enter->attributes = $data;
                    if (!$enter->save($server, $this->webFdTable, $this->phoneFdTable, $frame->fd)) {
                        printf("加入房间失败：%s\n", current($enter->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("加入房间失败：%s", current($model->getFirstErrors())));
                    }
                    if (empty($enter->uid)) {
                        printf("游客进入房间，推出成功消息\n");
                        Utils::sendSucc($server, $frame->fd, null, '进入房间成功');
                    }
                    break;
                case 'out':
                    //退出房间
                    $model = new OutRoomForm();
                    $model->attributes = $data;
                    if (!$model->save($server)) {
                        printf("退出房间失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("退出房间失败：%s", current($model->getFirstErrors())));
                    }
                    break;
                case 'prepare':
                    //准备比赛
                    $model = new PrepareForm();
                    $model->attributes = $data;
                    if (!$model->save($server)) {
                        printf("准备比赛失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("准备比赛失败：%s", current($model->getFirstErrors())));
                    }
                    break;
                case 'start':
                    //开始比赛
                    $model = new StartForm();
                    $model->attributes = $data;
                    if (!$model->save($server)) {
                        printf("开始比赛失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("开始比赛失败：%s", current($model->getFirstErrors())));
                    }
                    break;
                case 'play':
                    //摇动
                    $model = new PlayForm();
                    $model->attributes = $data;
                    if (!$model->save($server, $frame->fd)) {
                        printf("摇动失败：%s\n", current($model->getFirstErrors()));
                        Utils::sendFail($server, $frame->fd, sprintf("摇动失败：%s", current($model->getFirstErrors())));
                    }
                    break;
            }
        }
    }
    
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response) {
        $data = $request->get;
        if (empty($data['action'])) {
            YII_DEBUG && print("request:".json_encode($data)."\n");
        } else {
            YII_DEBUG && print("request:".$data['action']."\n");
            switch ($data['action']) {
                case 'auth_confirm':
                    $model = new AuthConfirmForm();
                    $model->attributes = $data;
                    if (!$model->save($this->serv, $this->webFdTable, $this->authFdTable)) {
                        printf("认证确认失败：%s\n", current($model->getFirstErrors()));
                        Utils::responseFail($response, sprintf("认证确认失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::responseSucc($response, null, '认证确认成功');
                    }
                    break;
                case 'enter':
                    //加入房间
                    $enter = new EnterRoomForm();
                    $enter->attributes = $data;
                    if (!$enter->save($this->serv, $this->webFdTable, $this->phoneFdTable)) {
                        printf("加入房间失败：%s\n", current($enter->getFirstErrors()));
                        Utils::responseFail($response, sprintf("加入房间失败：%s", current($enter->getFirstErrors())));
                    } else {
                        Utils::responseSucc($response, null, '加入房间成功');
                    }
                    break;
                case 'out':
                    //退出房间
                    $model = new OutRoomForm();
                    $model->attributes = $data;
                    if (!$model->save($this->serv)) {
                        printf("退出房间失败：%s\n", current($model->getFirstErrors()));
                        Utils::responseFail($response, sprintf("退出房间失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::responseSucc($response, null, '退出房间成功');
                    }
                    break;
                case 'prepare':
                    //准备比赛
                    $model = new PrepareForm();
                    $model->attributes = $data;
                    if (!$model->save($this->serv)) {
                        printf("准备比赛失败：%s\n", current($model->getFirstErrors()));
                        Utils::responseFail($response, sprintf("准备比赛失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::responseSucc($response, null, '准备比赛成功');
                    }
                    break;
                case 'start':
                    //开始比赛
                    $model = new StartForm();
                    $model->attributes = $data;
                    if (!$model->save($this->serv)) {
                        printf("开始比赛失败：%s\n", current($model->getFirstErrors()));
                        Utils::responseFail($response, sprintf("开始比赛失败：%s", current($model->getFirstErrors())));
                    } else {
                        Utils::responseSucc($response, null, '开始比赛成功');
                    }
                    break;
            }
        }
    }
    
    /**
     * 执行任务
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onTask()
     */
    public function onTask(\swoole_server $serv, $task_id, $src_worker_id, $data){
        //校验参数
        $data = json_decode($data, true);
        if (empty($data)) {
            //没有任何数据不做处理
            YII_DEBUG && printf("task_id:%d empty data\n", $task_id);
            \Yii::info(sprintf("task_id:%d empty data", $task_id), 'onTask');
            return;
        }
        if ($data['type']) {
            switch ($data['type']) {
                case 'send_room':
                    //通知给房间内所有人
                    YII_DEBUG && print("task:send_room\n");
                    SendTask::execute($serv, $data);
                    break;
                case 'send_result':
                    //发送比赛结果给房间所有人
                    YII_DEBUG && print("task:send_result\n");
                    SendResultTask::execute($serv, $data);
                    break;
            }
        }
    }

    /**
     * 任务执行完成
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onFinish()
     */
    public function onFinish(\swoole_server $serv, $task_id, $data){
        \Yii::info(sprintf("task_id:%d complete, data:%s", $task_id, $data), 'onFinish');
    }


}

