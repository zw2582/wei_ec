<?php
namespace paoma\console;

class Utils {
    
    public static function sendFail(\swoole_server $svr, $fd, $message = '', $data='') {
        $svr->send($fd, json_encode([
            'status'=>0,
            'message'=>$message,
            'data'=>$data
        ]));
    }
    
    public static function sendSucc(\swoole_server $svr, $fd, $data='', $message = '') {
        $svr->send($fd, json_encode([
            'status'=>1,
            'message'=>$message,
            'data'=>$data
        ]));
    }
}

