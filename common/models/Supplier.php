<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $tag
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $telephone
 * @property string $phone
 * @property string $email
 * @property integer $audit_status
 * @property integer $user_id
 * @property string $create_time
 * @property integer $status
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'address', 'user_id'], 'required'],
            [['audit_status', 'user_id', 'status'], 'integer'],
            [['create_time'], 'safe'],
            [['name', 'address'], 'string', 'max' => 255],
            [['tag', 'province', 'city', 'telephone', 'phone', 'email'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tag' => 'Tag',
            'province' => 'Province',
            'city' => 'City',
            'address' => 'Address',
            'telephone' => 'Telephone',
            'phone' => 'Phone',
            'email' => 'Email',
            'audit_status' => 'Audit Status',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'status' => 'Status',
        ];
    }
}
