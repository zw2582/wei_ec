<?php
namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use common\controllers\BasicController;
use common\models\Supplier;
use backend\models\supplierRegister;

class ApiController extends BasicController{
    
    /**
     * 登录
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->ajaxSuccess(null, '已经登录');
        }
        $model = new LoginForm();
        \Yii::$app->getRequest()->post();
        $model->setAttributes(Yii::$app->request->post());
        if ($model->login()) {
            return $this->ajaxSuccess(null, '登录成功');
        } else {
            return $this->ajaxFail(current($model->getFirstErrors()));
        }
    }
    
    /**
     * 退出登录
     * @return unknown
     * wei.w.zhou@integle.com
     * 2018年1月23日上午10:00:14
     */
    public function actionLoginOut() {
        Yii::$app->user->logout();
        
        return $this->ajaxSuccess(NULL, '退出成功');
    }
    
    /**
     * 店铺注册
     * 
     * wei.w.zhou@integle.com
     * 2018年1月23日上午10:10:48
     */
    public function actionRegist() {
        if (\Yii::$app->user->isGuest) {
            return $this->ajaxFail('请先登录', 'login');
        }
        $userId = \Yii::$app->user->id;
        $count = Supplier::find()->where(['user_id'=>$userId, 'status'=>1])->count();
        if ($count >= 5) {
            return $this->ajaxFail('您目前最多只能开设5个店铺');
        }
        //注册供应商
        $supp = new supplierRegister();
        $supp->attributes = \Yii::$app->request->post();
        $supp->user_id = $userId;
        if (!$supp->save()) {
            return $this->ajaxFail('店铺注册失败', $supp->getErrors());
        }
        
        return $this->ajaxSuccess($supp->attributes, '店铺注册成功');
    }
}

