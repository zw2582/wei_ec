<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_return_char".
 *
 * @property string $id
 * @property string $return_num
 * @property string $data
 * @property string $user_id
 * @property integer $source
 * @property string $create_time
 */
class OReturnChar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_return_char';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_num', 'data', 'user_id', 'source'], 'required'],
            [['user_id', 'source'], 'integer'],
            [['create_time'], 'safe'],
            [['return_num'], 'string', 'max' => 15],
            [['data'], 'string', 'max' => 500],
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
            'data' => 'Data',
            'user_id' => 'User ID',
            'source' => 'Source',
            'create_time' => 'Create Time',
        ];
    }
}
