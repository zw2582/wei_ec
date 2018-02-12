<?php
namespace console\modules\paoma\controllers;

use yii\console\Controller;
use console\modules\paoma\swoole\PaomaHandler;
use console\modules\paoma\swoole\WebSocketServer;
use console\modules\paoma\models\RequestData;

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
        $handler = new PaomaHandler();
        
        $webSocket = new WebSocketServer($handler);
    }
    
    public function actionTest() {
        $rd = new RequestData();
        $rd->attributes = [
            'action'=>'create',
            'uuid'=>'3232'
        ];
        if (!$rd->validate()) {
            print_r($rd->getErrors());
        } else {
            echo 'success';
        }
    }
}

