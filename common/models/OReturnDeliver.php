<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_return_deliver".
 *
 * @property string $id
 * @property string $return_num
 * @property string $express_num
 * @property string $express_nu
 * @property string $express_data
 * @property string $user_id
 * @property string $create_time
 */
class OReturnDeliver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_return_deliver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_num', 'express_num', 'express_nu', 'user_id'], 'required'],
            [['express_data'], 'string'],
            [['user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['return_num'], 'string', 'max' => 15],
            [['express_num'], 'string', 'max' => 32],
            [['express_nu'], 'string', 'max' => 64],
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
            'express_num' => 'Express Num',
            'express_nu' => 'Express Nu',
            'express_data' => 'Express Data',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
