<?php
namespace backend\controllers;

use backend\modules\product\models\ProductForm;
use common\models\SProductImg;
use yii\web\Controller;

class TestController extends Controller{
    
    public $enableCsrfValidation=false;
    
    public function actionIndex() {
        $id = [9];
        $data = SProductImg::find()->select('id')->where(['product_id'=>15])
        ->andWhere(['not in','id', $id])->column();
        
        var_dump($data);
    }
}

