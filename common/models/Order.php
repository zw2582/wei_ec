<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property string $supplier_id
 * @property string $order_num
 * @property integer $pay_status
 * @property integer $deliver_status
 * @property integer $revice_status
 * @property integer $user_id
 * @property string $create_time
 * @property integer $status
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'order_num', 'user_id'], 'required'],
            [['supplier_id', 'pay_status', 'deliver_status', 'revice_status', 'user_id', 'status'], 'integer'],
            [['create_time'], 'safe'],
            [['order_num'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'order_num' => 'Order Num',
            'pay_status' => 'Pay Status',
            'deliver_status' => 'Deliver Status',
            'revice_status' => 'Revice Status',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'status' => 'Status',
        ];
    }
}
