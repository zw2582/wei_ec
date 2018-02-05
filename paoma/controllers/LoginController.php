<?php
namespace paoma\controllers;

use yii\web\Controller;

/**
 * 微信登录控制
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日上午11:04:05
 */
class LoginController extends Controller{
    
    public function actionLogin() {
        if (\Yii::$app->weiauthor->login()) {
            
        }
    }
}

