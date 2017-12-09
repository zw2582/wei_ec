<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_package".
 *
 * @property string $id
 * @property integer $product_id
 * @property integer $supplier_id
 * @property integer $package_type_id
 * @property string $package
 * @property string $price
 * @property string $count
 */
class SPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'supplier_id', 'package_type_id', 'package', 'price'], 'required'],
            [['product_id', 'supplier_id', 'package_type_id', 'price', 'count'], 'integer'],
            [['package'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'supplier_id' => 'Supplier ID',
            'package_type_id' => 'Package Type ID',
            'package' => 'Package',
            'price' => 'Price',
            'count' => 'Count',
        ];
    }
}
