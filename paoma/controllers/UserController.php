<?php
namespace paoma\controllers;

use common\controllers\BasicController;
use paoma\models\PaomaUser;
use paoma\models\PaomaAuth;
use common\models\User;

/**
 * 用户管理
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午4:20:23
 */
class UserController extends BasicController{
    
    /**
     * 当前用户信息以及websocket地址
     * 
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:20:43
     */
    public function actionCurrent() {
        if (\Yii::$app->user->isGuest) {
            $data = [];
            $data['headimg'] = 'http://img.mp.itc.cn/upload/20170801/afc9309df32944129d0820121bd64c9e_th.jpg';
            $data['sex'] = 0;
            $data['uname'] = '未知';
            $data['uid']=0;
        } else {
            $data = PaomaUser::getUser(\Yii::$app->user->id);
        }
        $data['wsaddr'] = 'ws://120.79.30.72:9502';
        return $this->ajaxSuccess($data);
    }
    
    /**
     * web根据uid登录
     * 
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:27:05
     */
    public function actionWebLogin() {
        $uuid = \Yii::$app->request->get('uuid');
        $uid = PaomaAuth::get($uuid);
        if ($uid && is_numeric($uid)) {
            $user = User::findOne($uid);
            //登录
            if (!\Yii::$app->user->login($user)) {
                return $this->ajaxFail('登录失败');
            }
            //保存用户信息到redis
            PaomaUser::saveUser($uid, [
                'headimg'=>$user->headimgurl,
                'sex'=>$user->sex,
                'uname'=>$user->username
            ]);
            //删除认证信息
            PaomaAuth::del($uuid);
            return $this->ajaxSuccess($user->attributes, '登录成功');
        } else {
            return $this->ajaxFail('客户端还未授权登录');
        }
    }
}

