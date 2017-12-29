<?php
namespace frontend\controllers\test;

use common\controllers\BasicController;

class WsController extends BasicController{
    
    public function actionIndex() {
        return $this->render('/demo/ws_index');
    }
    
    public function actionChat() {
        $ws = new \swoole_websocket_server('0.0.0.0', '9501');
        
        $onlineQueue = new \swoole_table(1024);
        $onlineQueue->column('fd', \swoole_table::TYPE_INT);
        $onlineQueue->create();
        
        $ws->on('open', function(\swoole_websocket_server $ws, \swoole_http_request $request) use ($onlineQueue) {
            $email = $request->get['email'];
            if (empty($email)) {
                $ws->pause($fd);
                $ws->push($fd, 'need get email！');
                $ws->stop($fd);
                return;
            }
            $fd = $request->fd;
            $fdinfo = $onlineQueue->get($email);
            if ($fdinfo) {
                $ws->pause($fd);    //停止接受数据
                $ws->push($fd, 'exist email logined');
                $ws->stop($fd);     //停止当前fd
                return;
            }
            
            $onlineQueue->set($email, ['fd'=>$fd]);
            $ws->push($fd, 'welcome,'.$email);
        });
        
        $ws->on('message', function(\swoole_websocket_server $ws, \swoole_websocket_frame $frame) use ($onlineQueue) {
            $jsonData = $frame->data;
            $data = json_decode($jsonData, true);
            if (empty($data)) {
                $ws->push($frame->fd, '请输入内容');
                return;
            }
            $toEmail = $data['to'];
            
            $tofd = $onlineQueue->get($toEmail, 'fd');
            if ($ws->exist($tofd)) {
                $ws->push($tofd, $data['data']);
            } else {
                $ws->push($frame->fd, $toEmail.'已下线');
            }
        });
        
        $ws->on('close', function(\swoole_websocket_server $ws, $fd) use ($onlineQueue) {
            foreach ($onlineQueue as $key=>$data) {
                if ($data['fd'] == $fd) {
                    $onlineQueue->del($key);
                }
            }
        });
        
        $ws->start();
    }
}

