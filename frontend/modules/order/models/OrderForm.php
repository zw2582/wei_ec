<?php
namespace frontend\models;

use yii\base\Model;
use common\models\Order;

/**
 * 订单表单
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月8日下午7:45:38
 */
abstract class OrderForm extends Order{
    
    /**
     * 提交订单
     * 
     * wei.w.zhou@integle.com
     * 2017年12月8日下午7:46:01
     */
    public abstract function submit();
}

