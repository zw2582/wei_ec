<?php
namespace common\controllers;

use yii\web\Controller;

class BasicController extends Controller{
    
    public $enableCsrfValidation=false;
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (YII_DEBUG) {
            header('Access-Control-Allow-Origin:http://192.168.1.101:8080');
            header('Access-Control-Allow-Credentials:true');
        }
        return true;
    }
    
    //ajax返回成功
    public function ajaxSuccess($data, $message=NULL) {
        return $this->ajaxReturn(1, $data, $message);
    }
    
    //ajax返回失败
    public function ajaxFail($message, $data=NULL) {
        return $this->ajaxReturn(0, $data, $message);
    }
    
    //ajax返回失败
    public function ajaxLogin() {
        return $this->ajaxReturn(2, NULL, '请先登录');
    }
    
    public function ajaxReturn($status, $data, $message) {
        header("Content-Type:application/json; charset=UTF-8");
        echo json_encode([
            'status'=>$status,
            'data'=>$data,
            'message'=>$message
        ], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        die;
    }
}

