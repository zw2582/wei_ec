<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_o_product".
 *
 * @property integer $id
 * @property integer $o_product_id
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $user_id
 * @property string $create_time
 */
class LogOProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_o_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'o_product_id', 'type', 'description', 'user_id', 'create_time'], 'required'],
            [['id', 'o_product_id', 'type', 'user_id'], 'integer'],
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
            'o_product_id' => 'O Product ID',
            'type' => 'Type',
            'description' => 'Description',
            'data' => 'Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
