<?php
namespace backend\modules\product\models;

use common\models\SProduct;
use yii\base\Model;
use common\models\SProductCatalog;
use common\models\SCatalog;

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
    
    //商品类型id
    public $catalog_id;
    
    /**
     * @var array 商品规格
     * @see PackageForm
     */
    public $packages;
    
    /**
     * @var array 商品图片
     * @see ProductImageForm
     */
    public $images;
    
    public function rules() {
        return [
            [['name','number'], 'required'],
            [['catalog_id'], 'integer'],
            [['description', 'package', 'images'], 'safe']
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
        //保存商品类型
        if (is_int($this->catalog_id)) {
            if(!$this->saveCatalog($supplierId, $product->id, $this->catalog_id)) {
                \Yii::info('保存商品类型错误', __METHOD__);
                $transaction->rollBack();
                return false;
            }
        }
        //保存商品图片
        if (is_array($this->images)) {
            foreach ($this->images as $image) {
                $proImgForm = new ProductImageForm();
                $proImgForm->attributes = $image;
                if (!$proImgForm->save($product->id)) {
                    $this->addErrors($proImgForm->getErrors());
                    $transaction->rollBack();
                    return false;
                }
            }
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
    
    /**
     * 管理商品分类
     * 一个商品只有一个具体分类；保存分类时需要保存它关联的父分类，删除或者替换时需要移除原来的从父到子的所有分类
     * @param integer $supplierId
     * @param integer $productId
     * @param integer $catalogId
     * wei.w.zhou@integle.com
     * 2018年1月24日上午10:42:26
     */
    public function saveCatalog($supplierId, $productId, $catalogId) {
        //查询类型是否存在
        $catalog = SCatalog::findOne($catalogId);
        if (!$catalog || $catalog['supplier_id']!=$supplierId) {
            $this->addError('catalog', '商品类型不存在');
            return FALSE;
        }
        //查询现有商品的所有分类
        $oldCatalogIds = SProductCatalog::find()->select('catalog_id')
            ->where(['product_id'=>$productId])->column();
        $newCatalogIds = SCatalog::findRecursiveId($catalogId);
        //求原分类数组与新分类数组的差集，并删除
        $diff = array_diff($oldCatalogIds, $newCatalogIds);
        if ($diff) {
            SProductCatalog::deleteAll(['catalog_id'=>$diff]);
        }
        //求新分类数组与原分类数组的差集，并新增
        $diff = array_diff($newCatalogIds, $oldCatalogIds);
        if ($diff) {
            $transcation = \Yii::$app->db->beginTransaction();
            foreach ($diff as $dif) {
                $sproCatalog = new SProductCatalog(['product_id'=>$productId, 'catalog_id'=>$dif]);
                if (!$sproCatalog->save()) {
                    $this->addErrors($sproCatalog->getErrors());
                    $transcation->rollBack();
                    return FALSE;
                }
            }
            $transcation->commit();
        }
        return true;
    }
}

