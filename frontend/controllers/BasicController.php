<?php
namespace frontend\controllers;

use yii\web\Controller;

class BasicController extends Controller{
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->weiauthor->login();
        }
        return true;
    }
}

