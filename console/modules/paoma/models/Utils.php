<?php
namespace console\modules\paoma\models;

class Utils {
    
    public static function sendFail($svr, $fd, $message = '', $data='') {
        if (!$svr->exist($fd)) {
            return;
        }
        $svr->push($fd, json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public static function sendSucc($svr, $fd, $data='', $message = '') {
        if (!$svr->exist($fd)) {
            return;
        }
        $svr->push($fd, json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public static function sendTask($serv, $roomNo, $data) {
        $serv->task(json_encode([
            'type'=>'send_room',
            'room_no'=>$roomNo,
            'message'=>$data
        ]));
    }
    
    public static function responseFail(\swoole_http_response $response, $message='', $data = '') {
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public static function responseSucc(\swoole_http_response $response, $data = '', $message='') {
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ]));
    }
}

