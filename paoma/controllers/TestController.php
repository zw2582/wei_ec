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
	setcookie("user", "Alex Porter", time()+3600);
	\Yii::$app->session->set('sdf',1);
	$_SESSION['gogo'] = 'sdfs';
	echo date('Y-m-d H:i:s', time());die;
        $redis = \Yii::$app->redis;
        
        $redis->set("cac", "caca", "ex", 8, "nx");
        echo $redis->get("cac");
        var_dump(\Yii::$app->user->identity);
    }
    
    public function actionLogin() {
        $user = User::findOne(1);
        \Yii::$app->user->login($user);
    }
}

