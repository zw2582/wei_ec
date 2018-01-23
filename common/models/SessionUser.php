<?php
namespace common\models;

use yii\web\User;

class SessionUser extends User{
    
    //当前供应商id
    private $supplierId = FALSE;
    
    //当前供应商信息
    private $supplier;
    
    //当前用户的所有供应商
    private $supplierList;
    
    public function init() {
        parent::init();
        
        //绑定登录之后的操作
        $this->on(self::EVENT_AFTER_LOGIN, [$this,'handleAfterLogin']);
    }
    
    /**
     * 根据session中的supplierId获取supplier信息
     * @return \common\models\Supplier
     * wei.w.zhou@integle.com
     * 2018年1月22日下午3:28:19
     */
    public function getSupplier() {
        $supplierId = $this->getSupplierId();
        if ($supplierId) {
            $this->supplier = Supplier::findOne($supplierId);
        }
        return $this->supplier;
    }
    
    /**
     * 获取session中的当前supplierId
     * @return boolean|unknown
     * wei.w.zhou@integle.com
     * 2018年1月22日下午3:28:35
     */
    public function getSupplierId() {
        if ($this->supplierId === FALSE) {
            $this->supplierId = isset($_SESSION['supplier_id'])?$_SESSION['supplier_id']:0;
        }
        return $this->supplierId;
    }
    
    /**
     * 当前用户的所有供应商
     * @return \yii\db\static[]
     * wei.w.zhou@integle.com
     * 2018年1月22日下午3:17:54
     */
    public function getSupplierList() {
        if ($this->id && empty($this->supplierList)) {
            $this->supplierList = Supplier::findAll(['user_id'=>$this->id, 'status'=>1]);
        }
        return $this->supplierList;
    }
    
    /**
     * 登录成功之后的操作
     * 
     * wei.w.zhou@integle.com
     * 2018年1月22日下午3:32:58
     */
    public function handleAfterLogin() {
        \Yii::info('登录成功之后的操作');
        //设置supplierId
        if ($this->id) {
            $this->supplier = Supplier::findOne(['user_id'=>$this->id, 'status'=>1]);
            $this->supplierId = $this->supplier->id;
            \Yii::info('设置当前供应商id：'.$this->supplierId);
            $_SESSION['supplier_id'] = $this->supplierId;
        }
    }
}