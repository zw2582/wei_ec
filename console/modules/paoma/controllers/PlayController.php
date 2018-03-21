<?php
namespace console\modules\paoma\controllers;

use yii\console\Controller;
use console\modules\paoma\swoole\WebSocketServer;

/**
 * 利用swoole建立的websocket服务端
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午3:00:10
 */
class PlayController extends Controller{
    
    /**
     * 建立链接
     * 
     * wei.w.zhou@integle.com
     * 2018年2月5日下午3:00:43
     */
    public function actionRun() {
	echo "start paoma websocket service\n";
        $webSocket = new WebSocketServer();
        $webSocket->start(true);
    }
    
    public function actionTest() {
        $phoneFdTable = new \swoole_table(1000);
        $phoneFdTable->column('fd', \swoole_table::TYPE_INT);
        $phoneFdTable->create();
        
        $phoneFdTable->set(1, ['fd'=>23]);
        
        $fd = $phoneFdTable->get(1, 'fd');
        
        var_dump($fd);
    }
}

