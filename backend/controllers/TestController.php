<?php
namespace backend\controllers;

use backend\modules\product\models\ProductForm;
use common\models\SProductImg;
use yii\web\Controller;
use common\models\SysDistinct;

class TestController extends Controller{
    
    public $enableCsrfValidation=false;
    
    public function actionIndex() {
        SysDistinct::getCascaderData();
    }
    
}

