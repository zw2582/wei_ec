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
        ], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
    
    public static function sendSucc($svr, $fd, $data='', $message = '') {
        if (!$svr->exist($fd)) {
            return;
        }
        $svr->push($fd, json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
    
    //发送一个任务，该任务可以给房间所有用户推送指定的信息
    public static function sendTask($serv, $roomNo, $data) {
        $serv->task(json_encode([
            'type'=>'send_room',
            'room_no'=>$roomNo,
            'message'=>$data
        ]));
    }
    
    //发送一个任务，该任务将比赛结果定时发送给房间所有用户，直到比赛结束
    public static function sendResultTask($serv, $roomNo) {
        $serv->task(json_encode([
            'type'=>'send_result',
            'room_no'=>$roomNo
        ]));
    }
    
    public static function responseFail(\swoole_http_response $response, $message='', $data = '') {
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
    
    public static function responseSucc(\swoole_http_response $response, $data = '', $message='') {
        $response->header('Content-Type', 'application/json');
        $response->header('Access-Control-Allow-Origin', '*');
        $response->end(json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
}

