<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log_s_package".
 *
 * @property string $id
 * @property string $package_id
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $user_id
 * @property string $create_time
 */
class LogSPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_s_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_id', 'type', 'user_id'], 'required'],
            [['package_id', 'type', 'user_id'], 'integer'],
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
            'package_id' => 'Package ID',
            'type' => 'Type',
            'description' => 'Description',
            'data' => 'Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
