<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_revice".
 *
 * @property string $id
 * @property string $order_num
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $user_id
 * @property string $create_time
 * @property integer $is_default
 */
class ORevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_revice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_num', 'province', 'city', 'address', 'contact_name', 'contact_phone', 'user_id'], 'required'],
            [['user_id', 'is_default'], 'integer'],
            [['create_time'], 'safe'],
            [['order_num'], 'string', 'max' => 15],
            [['province', 'city', 'contact_phone'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255],
            [['contact_name'], 'string', 'max' => 10],
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
            'province' => 'Province',
            'city' => 'City',
            'address' => 'Address',
            'contact_name' => 'Contact Name',
            'contact_phone' => 'Contact Phone',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'is_default' => 'Is Default',
        ];
    }
}
