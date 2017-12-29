<?php
namespace frontend\controllers\test;

use yii\web\Controller;

/**
 * AmazeUi
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月20日下午1:15:48
 */
class AmazeController extends Controller{
    
    public $layout = 'main_amaze';
    
    public function actionIndex() {
        return $this->render('index');
    }
}

