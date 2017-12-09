<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_return".
 *
 * @property integer $id
 * @property string $return_num
 * @property string $order_num
 * @property integer $price
 * @property integer $return_good
 * @property integer $repay_status
 * @property integer $audit_status
 * @property string $user_id
 * @property string $create_time
 * @property string $description
 */
class OReturn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_return';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'return_num', 'order_num', 'user_id'], 'required'],
            [['id', 'price', 'return_good', 'repay_status', 'audit_status', 'user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['return_num', 'order_num'], 'string', 'max' => 15],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'return_num' => 'Return Num',
            'order_num' => 'Order Num',
            'price' => 'Price',
            'return_good' => 'Return Good',
            'repay_status' => 'Repay Status',
            'audit_status' => 'Audit Status',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'description' => 'Description',
        ];
    }
}
