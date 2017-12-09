<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_payment".
 *
 * @property integer $id
 * @property string $order_num
 * @property string $serival_num
 * @property string $pay_type
 * @property integer $pay_status
 * @property integer $user_id
 * @property string $create_time
 */
class OPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_num', 'serival_num', 'pay_type', 'pay_status', 'user_id'], 'required'],
            [['id', 'pay_status', 'user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['order_num'], 'string', 'max' => 15],
            [['serival_num', 'pay_type'], 'string', 'max' => 32],
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
            'serival_num' => 'Serival Num',
            'pay_type' => 'Pay Type',
            'pay_status' => 'Pay Status',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
