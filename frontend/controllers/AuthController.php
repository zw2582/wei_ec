<?php
namespace frontend\controllers;

/**
 * 认证controller
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月5日下午2:08:31
 */
class AuthController extends BasicController{
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        return true;
    }
}

