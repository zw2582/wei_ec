<?php
namespace frontend\controllers\test;

use yii\web\Controller;

class ElementController extends Controller{
    
    public $layout = 'main_element';
    
    public function actionIndex() {
        return $this->render('index');
    }
}

