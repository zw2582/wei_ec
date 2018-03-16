<?php
namespace console\modules\paoma\swoole;

use yii\base\Model;

/**
 * websocket创建
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午4:52:27
 */
class WebSocketServer extends Model{
    
    public $serv;
    
    public $host = '0.0.0.0';
    
    public $port = 9502;
    
    public $max_request = 100;    //worker进程超过该请求数则重启
    
    public $max_conn = 100;   //服务器允许的最大连接数,超过该连接数的连接拒绝,一个连接占用224byte
    
    public $worker_num = 2;     //worker进程数量，每个进程占40M内存
    
    public $task_worker_num = 2;  //task进程数量
    
    public function __construct() {
        //创建websocket进程
	   echo "创建websocket进程\n";
        $this->serv = new \swoole_websocket_server($this->host, $this->port);
        
        //实例一个处理类
        $paomaHandler = new PaomaHandler($this->serv);
        //事件处理
        $this->serv->on('open', [$paomaHandler, 'onOpen']);
        $this->serv->on('message', [$paomaHandler, 'onMessage']);
        $this->serv->on('close', [$paomaHandler, 'onClose']);
        $this->serv->on('task', [$paomaHandler, 'onTask']);
        $this->serv->on('finish', [$paomaHandler, 'onFinish']);
        $this->serv->on('request', [$paomaHandler, 'onRequest']);
    }
    
    public function start($daemonize=true) {
        //设置websocket配置
        $this->serv->set([
            'daemonize'=>$daemonize?1:0,
            'log_file'=>'/tmp/paoma.log',
            'max_request'=>$this->max_request,
            'max_conn'=>$this->max_conn,
            'worker_num'=>$this->worker_num,
            'dispatch_mode'=>3,  //抢占分配
            'task_worker_num'=>$this->task_worker_num,
            'task_ipc_mode'=>3  //task争抢模式
        ]);
        
        //启动程序
        echo "启动程序\n";
        $this->serv->start();
    }
}

