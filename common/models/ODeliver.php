<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_deliver".
 *
 * @property string $id
 * @property string $order_num
 * @property string $express_num
 * @property string $express_nu
 * @property string $express_data
 * @property integer $user_id
 * @property string $create_time
 */
class ODeliver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_deliver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_num', 'express_num', 'express_nu', 'express_data', 'user_id'], 'required'],
            [['express_data'], 'string'],
            [['user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['order_num'], 'string', 'max' => 15],
            [['express_num', 'express_nu'], 'string', 'max' => 32],
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
            'express_num' => 'Express Num',
            'express_nu' => 'Express Nu',
            'express_data' => 'Express Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
