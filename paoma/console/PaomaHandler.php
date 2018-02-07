<?php
namespace paoma\console;

use swoole_http_request;
use swoole_websocket_server;
use paoma\console\models\RequestData;

/**
 * 跑马处理
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午2:36:01
 */
class PaomaHandler implements WebSocketHandler{
    
    public $webFdTable;
    
    public $phoneFdTable;
    
    public function __construct() {
        //创建web端fd句柄表
        $this->webFdTable = new \swoole_table(1000);
        $this->webFdTable->column('fd', \swoole_table::TYPE_INT);
        $this->webFdTable->create();
        
        $this->phoneFdTable = new \swoole_table(1000);
        $this->phoneFdTable->column('fd', \swoole_table::TYPE_INT);
        $this->phoneFdTable->create();
    }
    
    public function onClose(\swoole_server $server, int $fd, int $reactorId) {
        \Yii::info(sprintf("reactorId:%d close a fd:%d", $reactorId, $fd), 'onClose');
    }

    /**
     * 连接到websocket时，根据source保存uuid和fd
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onOpen()
     */
    public function onOpen(swoole_websocket_server $svr, swoole_http_request $req) {
        //保存uuid和fd
        $source = $req->get['source'];
        $uuid = $req->get['uuid'];
        $fd = $req->fd;
        if (empty($source) || empty($uuid) || !in_array($source, ['web', 'phone'])) {
            return Utils::sendFail($svr, $fd, '缺少必须参数source,uuid');
        }
        //保存uuid和fd
        $table = $source == 'web' ? $this->webFdTable : $this->phoneFdTable;
        $oldfd = $table->get($uuid);
        if ($oldfd !== false) {
            $svr->pause($oldfd);
            $svr->exist($oldfd);
            $svr->stop($oldfd, true);
        } else {
            $table->set($uuid, ['fd'=>$fd]);
        }
    }

    /**
     * 当获取到消息时，直接转发给task
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onMessage()
     */
    public function onMessage(\swoole_server $server, \swoole_websocket_frame $frame) {
        $server->task($frame->data);
    }
    
    /**
     * 执行任务
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onTask()
     */
    public function onTask(\swoole_server $serv, int $task_id, int $src_worker_id, $data){
        //校验参数
        $data = json_decode($data);
        if (empty($data)) {
            //没有任何数据不做处理
            \Yii::info(sprintf("task_id:%d empty data", $task_id), 'onTask');
            return;
        }
        $requestData = new RequestData();
        $requestData->attributes = $data;
        if (!$requestData->validate()) {
            \Yii::info(sprintf("task_id:%d valid fail:%s", $task_id, json_encode($requestData->getErrors())), 'onTask');
            return;
        }
        //分发任务
        return $requestData->process($this);
    }

    /**
     * 任务执行完成
     * {@inheritDoc}
     * @see \paoma\console\WebSocketHandler::onFinish()
     */
    public function onFinish(\swoole_server $serv, int $task_id, string $data){
        \Yii::info(sprintf("task_id:%d complete, data:%s", $task_id, $data), 'onFinish');
    }


}

