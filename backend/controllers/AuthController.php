<?php
namespace backend\controllers;

use common\controllers\BasicController;

/**
 * 认证控制器
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月22日下午3:08:15
 */
class AuthController extends BasicController{
    
    public $supplierId;
    
    public $userId;
    
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (\Yii::$app->user->isGuest) {
            return $this->ajaxLogin('请先登录', 'login');
            if (\Yii::$app->request->isAjax) {
                return $this->ajaxLogin('请先登录', 'login');
            }
            $this->redirect('/site/login');
            return false;
        }
        $this->supplierId = \Yii::$app->user->supplierId;
        $this->userId = \Yii::$app->user->id;
        if (empty($this->supplierId)) {
            return $this->ajaxFail('请注册一个供应商', 'regist_supplier');
            if (\Yii::$app->request->isAjax) {
                return $this->ajaxFail('请注册一个供应商', 'regist_supplier');
            }
            $this->redirect('/site/regist');
            return false;
        }
        return true;
    }
}

