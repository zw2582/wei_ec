<?php
namespace paoma\console;

use yii\base\Model;

/**
 * websocket创建
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午4:52:27
 */
class WebSocketServer extends Model{
    
    public static $onlineQueue;
    
    public static function begin() {
        //用uuid保存fd
        self::$onlineQueue = new \swoole_table(1024);
        self::$onlineQueue->column('fd', \swoole_table::TYPE_INT);    //保存socketid
        self::$onlineQueue->create();
    }
    
    public static function start() {
        self::begin();
        
        $ws = new \swoole_websocket_server('0.0.0.0', '9501');
        
        $ws->on('open', function (\swoole_websocket_server $ws, \swoole_http_request $request) {
            $uuid = $request->get['uuid'];
            $client = $request->get['clinet'];  //web,phone
            //校验参数
            if (empty($uuid) || empty($client)) {
                //停止接受数据
                $ws->pause($fd);
                $ws->push($fd, '请传递uuid');
                $ws->stop($fd);
                return;
            }
            //停止现有fd，保存新fd
            $fd = $request->fd;
            $fdinfo = $onlineQueue->get($email);
            if ($fdinfo) {
                $ws->pause($fd); // 停止接受数据
                $ws->push($fd, 'exist email logined');
                $ws->stop($fd); // 停止当前fd
            }
            
            $onlineQueue->set($email, [
                'fd' => $fd
            ]);
            $ws->push($fd, 'welcome,' . $email);
        });
            
            $ws->on('message', function (\swoole_websocket_server $ws, \swoole_websocket_frame $frame) use ($onlineQueue) {
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
                    $ws->push($frame->fd, $toEmail . '已下线');
                }
            });
                
                $ws->on('close', function (\swoole_websocket_server $ws, $fd) use ($onlineQueue) {
                    foreach ($onlineQueue as $key => $data) {
                        if ($data['fd'] == $fd) {
                            $onlineQueue->del($key);
                        }
                    }
                });
        
        $ws->start();
    }
}

