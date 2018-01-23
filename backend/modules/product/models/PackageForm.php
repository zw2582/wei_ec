<?php
namespace backend\modules\product\models;

use yii\base\Model;
use common\models\SPackage;
use common\models\SPackageType;

/**
 * 产品规格管理
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月23日下午4:03:44
 */
class PackageForm extends Model{
    
    public $package;
    
    public $package_type;
    
    public $price;
    
    public $count;
    
    public function rules() {
        return [
            [['package','package_type'], 'required'],
            ['price', 'number'],
            ['count', 'integer', 'min'=>1]
        ];
    }
    
    /**
     * 保存商品规格
     */
    public function save($supplierId, $productId, $userId) {
        \Yii::info('保存商品规格', __METHOD__);
        if (!$this->validate()) {
            \Yii::error('产品规格校验失败', __METHOD__);
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        //匹配package_type
        $packageType = SPackageType::findOne(['user_id'=>$userId, 'name'=>$this->package_type]);
        if (!$packageType) {
            $packageType->user_id = $userId;
            $packageType->name = $packageType;
            if (!$packageType->save()) {
                \Yii::error('新增商品规格类型失败', __METHOD__);
                $this->addErrors($packageType->getErrors());
                $transaction->rollBack();
                return false;
            }
        }
        //保存package
        $package = SPackage::findOne(['product_id'=>$productId, 
            'package'=>$this->package, 
            'package_type_id'=>$packageType->id]);
        if (!$package) {
            $package = new SPackage([
                'product_id'=>$productId, 
                'supplier_id'=>$supplierId,
                'package'=>$this->package,
                'package_type_id'=>$packageType->id
            ]);
        }
        $package->count = $this->count;
        $package->price = $this->price;
        if (!$package->save()) {
            \Yii::error('新增商品规格失败', __METHOD__);
            $this->addErrors($package->getErrors());
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }
}

