<?php
namespace paoma\controllers;

use yii\web\Controller;

class TestController extends Controller{
    
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionTest() {
        $redis = \Yii::$app->redis;
        
        $redis->set("cac", "caca", "ex", 8, "nx");
        echo $redis->get("cac");
    }
}

