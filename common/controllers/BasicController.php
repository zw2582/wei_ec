<?php
namespace common\controllers;

use yii\web\Controller;

class BasicController extends Controller{
    
    public $enableCsrfValidation=false;
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        header('Access-Control-Allow-Origin:http://127.0.0.1:8080');
        header('Access-Control-Allow-Credentials:true');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods:GET,POST,PATCH,PUT,OPTIONS');
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
        ]);
        die;
    }
}

