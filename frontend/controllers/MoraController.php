<?php
namespace frontend\controllers;

use common\controllers\BasicController;
use common\models\WxPlatform;
use yii\base\UserException;

/**
 * 猜拳
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月29日下午1:35:39
 */
class MoraController extends BasicController {
    
    const appid = 'wxcb612ed7920d6b98';
    
    const appsecret = 'a6507fa042aad7316974c3f1f7dde928';
    
    /**
     * 根据登录的code获取appid和session_key
     * @return unknown
     * wei.w.zhou@integle.com
     * 2017年12月29日下午1:58:10
     */
    public function actionLogin() {
        $code = \Yii::$app->request->get('code');
        try {
            $data = WxPlatform::getJscode2session(self::appid, self::appsecret, $code);
        } catch (UserException $e) {
            return $this->ajaxFail($e->getMessage());
        }
        return $this->ajaxSuccess($data, '获取session_key成功');
    }
}

