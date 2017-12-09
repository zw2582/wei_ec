<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_return_payment".
 *
 * @property string $id
 * @property string $return_num
 * @property string $type
 * @property string $description
 */
class OReturnPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_return_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_num', 'type'], 'required'],
            [['return_num'], 'string', 'max' => 15],
            [['type'], 'string', 'max' => 32],
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
            'type' => 'Type',
            'description' => 'Description',
        ];
    }
}
