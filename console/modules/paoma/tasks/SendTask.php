<?php
namespace console\modules\paoma\tasks;

use console\modules\paoma\models\Utils;
use paoma\models\PaomaRoomUsers;
use console\modules\paoma\swoole\PaomaHandler;
use paoma\models\PaomaRoomFd;

/**
 * 发送消息给房间所有用户
 * @author zhangjiao
 *
 */
class SendTask {
    
    public static function execute(\swoole_server $serv, $data) {
        $handler = PaomaHandler::getInstance();
        $roomNo = $data['room_no'];
        $message = $data['message'];
        $uids = PaomaRoomUsers::members($roomNo);
        
        foreach ($uids as $uid) {
            //获取phoneFd
            $fd = $handler->phoneFdTable->get($uid, 'fd');
            Utils::sendSucc($serv, $fd, $message);
            //获取webFd
            $fd = $handler->webFdTable->get($uid, 'fd');
            Utils::sendSucc($serv, $fd, $message);
        }
        //发送给游客
        $fds = PaomaRoomFd::members($roomNo);
        foreach ($fds as $fd) {
            if (!$serv->exist($fd)) {
                PaomaRoomFd::del($roomNo, $fd);
            }
            Utils::sendSucc($serv, $fd, $message);
        }
    }
}

