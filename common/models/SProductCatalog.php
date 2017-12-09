<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_product_catalog".
 *
 * @property string $id
 * @property string $product_id
 * @property string $catalog_id
 */
class SProductCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_product_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'catalog_id'], 'required'],
            [['product_id', 'catalog_id'], 'integer'],
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
            'catalog_id' => 'Catalog ID',
        ];
    }
}
