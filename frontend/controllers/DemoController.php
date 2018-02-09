<?php
namespace frontend\controllers;

use common\controllers\BasicController;
use yii\web\Controller;
use common\controllers\AuthController;
use common\models\WxPlatform;

class DemoController extends BasicController {
    
    const appid = 'wxcb612ed7920d6b98';
    
    const appsecret = 'a6507fa042aad7316974c3f1f7dde928';
    
    //首页
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionTest() {
        
        $user = [
            'real_name'=>'real_name',
            'nick_name'=>'nick_name',
            'name'=>'name'
        ];
        
        $caca = $user['real_name']?:($user['nick_name']?:$user['name']) ? : 'email';
        
        var_dump($caca);die;
        
        $ch = curl_init("https://www.juxinli.com/orgApi/rest/applications/liuju");
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json',
            'Accept: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "selected_website"=>[],
            "basic_info"=> [
                "name"=>"廖晓庆",
                "id_card_num"=>"422801198910213838",
                "cell_phone_num"=>"13858319176"
            ]
        ]));
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($ch);
        
        echo($result);die;
        
        /* $data = WxPlatform::getAccessToken(self::appid, self::appsecret);
        print_r($data);die; */
        
        $d = ['a'=>1,'b'=>2];
        $c = &$d['a'];
        $c = ['z'=>1];
        unset($c);
        print_r($d);
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

