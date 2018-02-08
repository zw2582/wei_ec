<?php
namespace paoma\controllers;

use paoma\models\PaomaUUid;
use paoma\models\PaomaUserRoom;
use paoma\models\Room;
use yii\web\Controller;
use common\controllers\BasicController;
use common\models\User;

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
     * 
     * wei.w.zhou@integle.com
     * 2018年2月8日上午9:42:48
     */
    public function actionLoginAuth() {
        $uid = \Yii::$app->request->post('uid');
        $uuid = \Yii::$app->request->post('uuid');
        if (empty($uid) || empty($uuid)) {
            return $this->ajaxFail("缺少必须参数");
        }
        //登录
        $user = User::findIdentity($uid);
        if (!\Yii::$app->user->login($user, 3600 * 24)) {
            return $this->ajaxFail('登录失败');
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
        return $this->ajaxSuccess(null, '认证成功');
    }
    
}

