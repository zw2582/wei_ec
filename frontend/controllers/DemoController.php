<?php
namespace frontend\controllers;

use yii\web\Controller;

class DemoController extends Controller {
    
    //首页
    public function actionIndex() {
        return $this->render('index');
    }
    
    //收货地址
    public function actionAddress() {
        $this->layout = 'main_no_bottom';
        return $this->render('address');
    }
    
    public function actionContent() {
        $this->layout = 'main_no_bottom';
        return $this->render('content');
    }
    
    public function actionDetail() {
        $this->layout = 'main_no_bottom';
        return $this->render('detail');
    }
    
    public function actionKlist() {
        $this->layout = 'main_no_bottom';
        return $this->render('klist.php');
    }
    
    public function actionMap() {
        $this->layout = 'main_no_bottom';
        return $this->render('map.php');
    }
    
    public function actionMember() {
        return $this->render('member.php');
    }
    
    public function actionOrder() {
        $this->layout = 'main_no_bottom';
        return $this->render('order.php');
    }
    
    public function actionSearch() {
        $this->layout = 'main_no_bottom';
        return $this->render('search.php');
    }
    
    public function actionSpeak() {
        return $this->render('speak.php');
    }
    
    public function actionWe() {
        return $this->render('we.php');
    }
    
    public function actionYhq() {
        $this->layout = 'main_b';
        return $this->render('yhq.php');
    }
    
}

