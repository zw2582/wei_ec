<?php
namespace console\modules\paoma\models;

class Utils {
    
    public static function sendFail($svr, $fd, $message = '', $data='') {
        $svr->push($fd, json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public static function sendSucc($svr, $fd, $data='', $message = '') {
        $svr->push($fd, json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ]));
    }
}

