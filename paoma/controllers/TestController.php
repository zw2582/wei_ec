<?php
namespace paoma\controllers;

use yii\web\Controller;
use common\models\User;

class TestController extends Controller{
    
    public function actionIndex() {
        if (\Yii::$app->user->isGuest) {
            $user = User::findOne(1);
            \Yii::$app->user->login($user);
        }
        return $this->render('index');
    }
    
    public function actionTest() {
        var_dump(\Yii::$app->user->identity);
    }
    
    public function actionLogin() {
        $user = User::findOne(1);
        \Yii::$app->user->login($user);
    }
}

