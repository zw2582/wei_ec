<?php
namespace paoma\modules\phone\controllers;

use yii\web\Controller;
use yii\base\UserException;
use paoma\models\PaomaUUid;
use paoma\models\PaomaUser;
use paoma\models\Room;

/**
 * 手机端用户登录
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午1:23:31
 */
class UserController extends Controller{
    
    /**
     * 手机端扫码登录，绑定uuid
     * @throws UserException
     * @return string
     * wei.w.zhou@integle.com
     * 2018年2月5日下午1:35:36
     */
    public function actionLogin() {
        if (\Yii::$app->user->isGuest) {
            $uuid = \Yii::$app->request->get('uuid');
            $action = \Yii::$app->request->get('action');
            
            if (empty($uuid) || empty($action)) {
                throw new UserException('缺少必须参数');
            }

            $_COOKIE['uuid'] = $uuid;
            $_COOKIE['action'] = $action;
            \Yii::$app->weiauthor->login();
        } else {
            $uuid = $_COOKIE['uuid'];
            $action = $_COOKIE['action'];
            PaomaUUid::setByUid(\Yii::$app->user->id, $uuid);
            
            //获取跑马用户信息
            $paomaUser = PaomaUser::current();
            //获取跑马房间信息
            $roomNo = $paomaUser->currentRoomNo();
            $room = Room::findOne($roomNo);
            return $this->render('/room', [
                'paoma_user'=>$paomaUser,
                'uuid'=>$uuid,
                'action'=>$action,
                'room'=>$room
            ]);
        }
    }
}

