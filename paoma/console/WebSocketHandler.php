<?php
namespace paoma\console;

use swoole_http_request;
use swoole_websocket_server;

/**
 * webSocket处理器
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午2:30:21
 */
interface WebSocketHandler {
    
    /**
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数。
     * @param swoole_websocket_server $svr
     * @param swoole_http_request $req 是一个Http请求对象，包含了客户端发来的握手请求信息
     * wei.w.zhou@integle.com
     * 2018年2月7日下午2:27:54
     */
    public function onOpen(swoole_websocket_server $svr, swoole_http_request $req);
    
    /**
     * 当服务器收到来自客户端的数据帧时会回调此函数。
     * @param \swoole_server $server
     * @param \swoole_websocket_frame $frame
     * wei.w.zhou@integle.com
     * 2018年2月7日下午2:28:20
     */
    public function onMessage(\swoole_server $server, \swoole_websocket_frame $frame);
    
    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数
     * @param \swoole_server $server
     * @param int $fd
     * @param int $reactorId
     * wei.w.zhou@integle.com
     * 2018年2月7日下午2:30:16
     */
    public function onClose(\swoole_server $server, int $fd, int $reactorId);
    
    public function onTask(\swoole_server $serv, int $task_id, int $src_worker_id, $data);
    
    public function onFinish(\swoole_server $serv, int $task_id, string $data);
}

