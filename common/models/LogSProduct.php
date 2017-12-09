<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_s_product".
 *
 * @property string $id
 * @property string $product_id
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $user_id
 * @property string $create_time
 */
class LogSProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_s_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'type', 'user_id'], 'required'],
            [['product_id', 'type', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['create_time'], 'safe'],
            [['description'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'description' => 'Description',
            'data' => 'Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
