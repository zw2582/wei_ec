<?php
namespace paoma\modules\phone\controllers;

use yii\web\Controller;
use yii\base\UserException;
use paoma\models\PaomaUUid;
use paoma\models\PaomaUser;
use paoma\models\PaomaUserRoom;
use paoma\models\Room;
use console\modules\paoma\models\PaomaAuth;

/**
 * 手机端用户登录
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午1:23:31
 */
class SiteController extends Controller{
    
    /**
     * 跑马首页
     * @return string
     * wei.w.zhou@integle.com
     * 2018年2月9日下午3:30:44
     */
    public function actionIndex() {
        return $this->render('/index');
    }
    
    /**
     * 加入房间
     * 
     * wei.w.zhou@integle.com
     * 2018年2月9日下午3:30:35
     */
    public function actionJoin() {
        $roomNo = \Yii::$app->request->get('room_no');  //必填
        $uuid = \Yii::$app->request->get('uuid');
        
        if (empty($roomNo)) {
            throw new UserException('缺少房间号');
        }
        //查看当前用户
        $paomaUser = PaomaUser::current($uuid);
        
        //加入房间
        Room::join($roomNo, $paomaUser);
        
        $room =  Room::findOne($roomNo);
        
        return $this->render('room', [
            'user'=>$paomaUser,
            'room'=>$room
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
        if (!\Yii::$app->user->isGuest) {
            return $this->actionJoin();
        }
        
        //登录
        if (!\Yii::$app->weiauthor->login()) {
            return;
        }
        
        $user = PaomaUser::current();
        $roomNo = $user->currentRoomNo();
        $room =  Room::findOne($roomNo);
        //判断是否需要认证确认
        if (PaomaAuth::get($uuid)) {
            $authConfirm = true;
        }
        //绑定用户认证信息
        PaomaUUid::setByUid($this->uid, $this->uuid);
        //获取用户房间
        $roomNo = PaomaUserRoom::get($this->uuid);
        if ($roomNo) {
            //判断是否是房主,是则将房间类型改为待开始
            $room = Room::findOne($roomNo);
            if ($room && $room['uuid'] == $this->uuid) {
                if ($room['isactive'] == 0) {
                    Room::updateStatus($roomNo, 1);
                }
                if ($room['uid'] != $uid) {
                    Room::updateUid($roomNo, $uid);
                }
            }
        }
        
        return $this->render('room', [
            'user'=>$paomaUser,
            'room'=>$room,
            'auth_confirm'=>$authConfirm?true:false
        ]);
    }
}

