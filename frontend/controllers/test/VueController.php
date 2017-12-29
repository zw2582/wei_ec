<?php
namespace frontend\controllers\test;

use yii\web\Controller;

class VueController extends Controller{
    
    public $layout = 'main_vue';
    
    public function actionIndex() {
        $data = [
            'a'=>[
                'name'=>'abc',
                'message'=>'bdd',
                'sex'=>'ssdf'
            ]
        ];
        return $this->render('index', ['params'=>json_encode($data)]);
    }
    
    public function actionBit() {
        $str1 = 12;
        $str2 = 13;
        
        $result = $str1 & $str2;
        var_dump($result);
    }
}

