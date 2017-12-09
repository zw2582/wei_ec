<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_product".
 *
 * @property string $id
 * @property string $order_num
 * @property integer $product_id
 * @property string $name
 * @property string $package
 * @property string $package_type
 * @property string $original_price
 * @property integer $price
 * @property integer $count
 * @property string $img_save_path
 * @property string $img_save_name
 */
class OProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_num', 'product_id', 'name', 'original_price', 'price', 'count', 'img_save_path', 'img_save_name'], 'required'],
            [['product_id', 'original_price', 'price', 'count'], 'integer'],
            [['order_num'], 'string', 'max' => 15],
            [['name'], 'string', 'max' => 64],
            [['package', 'package_type', 'img_save_name'], 'string', 'max' => 32],
            [['img_save_path'], 'string', 'max' => 9],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_num' => 'Order Num',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'package' => 'Package',
            'package_type' => 'Package Type',
            'original_price' => 'Original Price',
            'price' => 'Price',
            'count' => 'Count',
            'img_save_path' => 'Img Save Path',
            'img_save_name' => 'Img Save Name',
        ];
    }
}
