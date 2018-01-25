<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_product".
 *
 * @property integer $id
 * @property string $number
 * @property string $price
 * @property string $name
 * @property integer $supplier_id
 * @property string $description
 * @property integer $user_id
 * @property string $create_time
 * @property integer $status
 */
class SProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'price', 'name', 'supplier_id', 'user_id'], 'required'],
            [['price', 'supplier_id', 'user_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['create_time'], 'safe'],
            [['number'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'price' => 'Price',
            'name' => 'Name',
            'supplier_id' => 'Supplier ID',
            'description' => 'Description',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'status' => 'Status',
        ];
    }
}
