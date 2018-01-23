<?php
namespace backend\modules\product\models;

use common\models\SProduct;
use yii\base\Model;

/**
 * 产品新增
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月23日下午3:29:20
 */
class ProductForm extends Model{
    
    public $name;
    
    public $number;
    
    public $description;
    
    /**
     * @see PackageForm
     */
    public $packages;
    
    public function rules() {
        return [
            [['name','number'], 'required'],
            [['description', 'package'], 'safe']
        ];
    }
    
    public function save($supplierId, $userId) {
        \Yii::info('保存商品', __METHOD__);
        if (!$this->validate()) {
            \Yii::info('保存商品，校验参数错误', __METHOD__);
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        //保存产品
        $product = SProduct::findOne(['supplier_id'=>$supplierId, 'number'=>$this->number]);
        if ($product) {
            $product->status = 1;
        } else {
            $product = new SProduct(['supplier_id'=>$supplierId, 'user_id'=>$userId]);
            $product->number = $this->number;
        }
        $product->name = $this->name;
        $product->description = $this->description;
        if (!$product->save()) {
            \Yii::info('保存商品错误', __METHOD__);
            $this->addErrors($product->getErrors());
            $transaction->rollBack();
            return false;
        }
        //保存产品包装
        if (is_array($this->packages)) {
            foreach ($this->packages as $packageData) {
                $packageForm = new PackageForm();
                $packageForm->attributes = $packageData;
                if (!$packageForm->save($supplierId, $product->id, $userId)) {
                    $this->addErrors($packageForm->getErrors());
                    $transaction->rollBack();
                    return false;
                }
            }
        }
        $transaction->commit();
        return true;
    }
}

