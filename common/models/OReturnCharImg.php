<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "o_return_char_img".
 *
 * @property integer $id
 * @property integer $o_r_char_id
 * @property string $save_path
 * @property string $save_name
 * @property string $real_name
 */
class OReturnCharImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_return_char_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'o_r_char_id', 'save_path', 'save_name', 'real_name'], 'required'],
            [['id', 'o_r_char_id'], 'integer'],
            [['save_path'], 'string', 'max' => 9],
            [['save_name'], 'string', 'max' => 32],
            [['real_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'o_r_char_id' => 'O R Char ID',
            'save_path' => 'Save Path',
            'save_name' => 'Save Name',
            'real_name' => 'Real Name',
        ];
    }
}
