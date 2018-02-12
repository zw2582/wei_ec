<?php
namespace paoma\controllers;

use paoma\models\PaomaUUid;
use paoma\models\PaomaUserRoom;
use paoma\models\Room;
use yii\web\Controller;
use common\controllers\BasicController;
use common\models\User;
use paoma\models\PaomaUser;
use yii\base\UserException;

/**
 * 微信登录控制
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日上午11:04:05
 */
class LoginController extends BasicController{
    
    public function actionLogin() {
        if (\Yii::$app->weiauthor->login()) {
            
        }
    }
    
    /**
     * 通过微信认证信息登录
     * /login/login-auth
     * 
     * wei.w.zhou@integle.com
     * 2018年2月8日上午9:42:48
     */
    public function actionLoginAuth() {
        $uid = \Yii::$app->request->get('uid');
        if (empty($uid)) {
            throw new UserException("缺少必须参数");
        }
        //登录
        $user = User::findIdentity($uid);
        if (!\Yii::$app->user->login($user)) {
            throw new UserException("登录失败");
        }
        
        $user = PaomaUser::current();
        $roomNo = $user->currentRoomNo();
        
        if (!empty($roomNo)) {
            return $this->redirect('/room/join?room_no='.$roomNo);
        } else {
            return $this->redirect('/site/index');
        }
    }
    
}

