<?php
namespace backend\controllers;

use backend\modules\product\models\ProductForm;
use yii\web\Controller;

class TestController extends Controller{
    
    public $enableCsrfValidation=false;
    
    public function actionIndex() {
        $productForm = new ProductForm();
        $productForm->setAttributes(\Yii::$app->request->post(), false);
        
        var_dump($productForm->attributes);
        var_dump($productForm->validate());
    }
}

