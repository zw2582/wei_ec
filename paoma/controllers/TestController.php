<?php
namespace paoma\controllers;

use yii\web\Controller;

class TestController extends Controller{
    
    public function actionIndex() {
        $redis = \Yii::$app->redis;
        
        $data = $redis->zrevrangebyscore('a', 5, 0, 'withscores', 'limit', 1, 3);
       
        $result = [];
        foreach ($data as $k=>$v) {
            if ($k % 2 == 0) {
                $result[$v] = $data[$k+1];
            }
        }
        
            var_dump($result);
    }
}

