<?php
namespace common\controllers;

use yii\web\Controller;

class BasicController extends Controller{
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
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

