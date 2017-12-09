<?php

namespace frontend\modules\order\controllers;

use common\controllers\AuthController;

/**
 * 订单处理
 */
class OrderController extends AuthController {
    /**
     * 订单列表
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /**
     * 订单提交
     * 
     * wei.w.zhou@integle.com
     * 2017年12月9日下午3:39:47
     */
    public function actionSubmit() {
        
    }
    
    /**
     * 订单详情
     * 
     * wei.w.zhou@integle.com
     * 2017年12月9日下午3:40:02
     */
    public function actionDetail() {
        
    }
}
