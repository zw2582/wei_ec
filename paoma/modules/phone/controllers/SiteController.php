<?php
namespace paoma\modules\phone\controllers;

use yii\web\Controller;
use yii\base\UserException;
use paoma\models\PaomaUUid;
use paoma\models\PaomaUser;
use paoma\models\PaomaUserRoom;
use paoma\models\Room;
use console\modules\paoma\models\PaomaAuth;
use common\controllers\BasicController;
use common\models\User;
use paoma\models\PaomaRoomUsers;
use console\modules\paoma\models\PaomaRoomScore;

/**
 * 手机端用户登录
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午1:23:31
 */
class SiteController extends BasicController{
    
    public function actionTest() {
        $session = \Yii::$app->session;
        
        $session->set('caca', '2323');
        
//         var_dump(\Yii::$app->user->identity);
// $_SESSION['gogog']= 2;
    var_dump($_SESSION);
    }
    
    /**
     * 返回用户信息
     * 
     * wei.w.zhou@integle.com
     * 2018年2月12日下午2:09:48
     */
    public function actionUser() {
        $data = [];
        
        if (\Yii::$app->user->isGuest) {
	    return $this->ajaxFail('登录失败');
        }
        if (!\Yii::$app->user->isGuest) {
            $user = \Yii::$app->user->identity;
            $data['headimg'] = $user['headimgurl'];
            $data['sex'] = $user['sex'];
            $data['username'] = $user['username'];
        } else {
            $data['headimg'] = 'http://img.mp.itc.cn/upload/20170801/afc9309df32944129d0820121bd64c9e_th.jpg';
            $data['sex'] = 0;
            $data['username'] = '未知';
        }
        $paomaUser = PaomaUser::current();
        $paomaUser->saveUser();
        $data['uid'] = $paomaUser['uid'];
        $data['uuid'] = $paomaUser['uuid'];
        $data['room_no'] = $paomaUser->currentRoomNo();
        $data['wsaddr'] = 'ws://120.79.30.72:9502';
        //判断是否需要认证确认
        if (PaomaAuth::get($paomaUser->uuid)) {
            $data['auth_confirm'] = true;
        } else {
            $data['auth_confirm'] = false;
        }
        return $this->ajaxSuccess($data);
    }
    
    /**
     * 手机端首页的唯一入口跑马首页
     * @return string
     * wei.w.zhou@integle.com
     * 2018年2月9日下午3:30:44
     */
    public function actionIndex() {
        $roomNo = \Yii::$app->request->get('room_no');
        $uuid = \Yii::$app->request->get('uuid');
        
        if (\Yii::$app->user->isGuest) {
           if(!\Yii::$app->weiauthor->login()) {
		return;
	   }
        }
        
        $url = '/index.html';
        if ($roomNo) {
            $url .= '#/room?room_no='.$roomNo;
        }
        if ($uuid) {
            $paomaUser = PaomaUser::current($uuid);
        } else {
            $paomaUser = PaomaUser::current();
        }
        
        return $this->renderPartial($url);
    }
    
    /**
     * 加入房间
     * 
     * wei.w.zhou@integle.com
     * 2018年2月9日下午3:30:35
     */
    public function actionJoin() {
        //查看当前用户
        $paomaUser = PaomaUser::current();
        
        $roomNo = \Yii::$app->request->get('room_no');  //必填
        
        if (empty($roomNo)) {
            if ($paomaUser->currentRoomNo()) {
                $roomNo = $paomaUser->currentRoomNo();
            } else {
                return $this->ajaxFail("请输入房间号");
            }
        }
        $room = Room::findOne($roomNo);
        if (empty($room)) {
            return $this->ajaxFail('房间不存在');
        }
        
        //判断用户是否为房主
        $currentRoomNo = $paomaUser->currentRoomNo();
        if ($currentRoomNo) {
            $oldRoom = Room::findOne($currentRoomNo);
            if ($room['room_no'] != $oldRoom['room_no'] && $oldRoom['uuid'] == $paomaUser->uuid) {
                return $this->ajaxFail('您已经创建了一个房间，请先退出');
            }
        }
        
        //加入房间
        Room::join($roomNo, $paomaUser);
        
        //获取房主信息
        $master = User::findOne($room['uid']);
        
        return $this->ajaxSuccess([
            'role'=>$room['uuid'] == $paomaUser->uuid ? 'master' : 'player',
            'uuid'=>$paomaUser->uuid,
            'room_no'=>$roomNo,
            'isactive'=>$room['isactive'],
            'master_name'=>$master['username'],
            'master_headimg'=>$master['headimgurl'],
            'master_sex'=>$master['sex'],
            'member_count'=>PaomaRoomUsers::count($roomNo),
            'members'=>PaomaRoomUsers::listByUUid($roomNo, $paomaUser->uuid)
        ]);
    }
    
    /**
     * 创建房间
     * 
     * wei.w.zhou@integle.com
     * 2018年2月11日下午2:30:22
     */
    public function actionCreate() {
        //查看当前跑马用户信息
        $paomaUser = PaomaUser::current();
        if (empty($paomaUser->uid)) {
            return $this->ajaxFail('请先登录');
        }
        
        //判断用户是否为房主
        $currentRoomNo = $paomaUser->currentRoomNo();
        if ($currentRoomNo) {
            $oldRoom = Room::findOne($currentRoomNo);
            if ($oldRoom['uuid'] == $paomaUser->uuid) {
                return $this->ajaxFail('您已经创建了一个房间，请先退出');
            }
        }
        
        //创建房间
        $roomNo = Room::create($paomaUser);
        
        return $this->ajaxSuccess([
            'room_no'=>$roomNo,
            'uuid'=>$paomaUser->uuid
        ]);
    }
    
    /**
     * 手机端扫码登录，绑定uuid
     * @throws UserException
     * @return string
     * wei.w.zhou@integle.com
     * 2018年2月5日下午1:35:36
     */
    public function actionAuth() {
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->weiauthor->login();
        }
        $paomaUser = PaomaUser::current();
        $roomNo = $paomaUser->currentRoomNo();
        $room =  Room::findOne($roomNo);
        
        $paomaUser->saveUser();
        //判断是否是房主,是则将房间类型改为待开始
        if ($room) {
            if ($room && $room['uuid'] == $this->uuid) {
                if ($room['isactive'] == 0) {
                    Room::updateStatus($roomNo, 1);
                }
                if ($room['uid'] != $uid) {
                    Room::updateUid($roomNo, $uid);
                }
            }
        }
        
        return $this->actionIndex();
    }
    
    /**
     * 返回成员详细信息
     * 
     * wei.w.zhou@integle.com
     * 2018年2月13日下午1:51:49
     */
    public function actionUsers() {
        $uuid = \Yii::$app->request->get('uuid');
        $roomNo = \Yii::$app->request->get('room_no');
        if (empty($uuid) || empty($roomNo)) {
            return $this->ajaxFail('缺少参数');
        }
        
        $data = PaomaRoomUsers::listByUUid($roomNo, $uuid);
        return $this->ajaxSuccess($data);
    }
}

