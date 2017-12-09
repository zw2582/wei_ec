<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_o_return".
 *
 * @property integer $id
 * @property string $return_num
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $user_id
 * @property string $create_time
 */
class LogOReturn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_o_return';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'return_num', 'type', 'description', 'user_id', 'create_time'], 'required'],
            [['id', 'type', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['create_time'], 'safe'],
            [['return_num'], 'string', 'max' => 15],
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
            'return_num' => 'Return Num',
            'type' => 'Type',
            'description' => 'Description',
            'data' => 'Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
