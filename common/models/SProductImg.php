<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_product_img".
 *
 * @property integer $id
 * @property string $product_id
 * @property string $save_path
 * @property string $save_name
 * @property string $real_name
 */
class SProductImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_product_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'save_path', 'save_name', 'real_name'], 'required'],
            [['id', 'product_id'], 'integer'],
            [['save_path'], 'string', 'max' => 9],
            [['save_name'], 'string', 'max' => 32],
            [['real_name'], 'string', 'max' => 255],
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
            'save_path' => 'Save Path',
            'save_name' => 'Save Name',
            'real_name' => 'Real Name',
        ];
    }
}
