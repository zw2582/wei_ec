<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_order".
 *
 * @property integer $id
 * @property string $order_num
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $user_id
 * @property string $create_time
 */
class LogOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_num', 'type', 'description', 'user_id', 'create_time'], 'required'],
            [['id', 'type', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['create_time'], 'safe'],
            [['order_num'], 'string', 'max' => 15],
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
            'order_num' => 'Order Num',
            'type' => 'Type',
            'description' => 'Description',
            'data' => 'Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
