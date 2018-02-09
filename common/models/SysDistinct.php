<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sys_distinct".
 *
 * @property string $number
 * @property string $name
 * @property string $parent
 */
class SysDistinct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_distinct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number'], 'required'],
            [['number', 'parent'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number' => 'Number',
            'name' => 'Name',
            'parent' => 'Parent',
        ];
    }
    
    public static function getCascaderData() {
        $data = [];
        $i = 0;
        
        $data=self::find()->select('name as value, name label,number')->where(['parent'=>'000000'])->asArray()->all();
        $tmp = &$data;
        $old = &$data;
        
        do {
            $t = self::find()->select('name as value, name label, number')->where(['parent'=>$tmp[$i]['number']])->asArray()->all();
            if ($t) {
                $tmp[$i]['child'] = $t;
                $tmp = &$data[$i]['child'];
                $old = &$data;
            } else {
                $i++;
                $tmp = &$old;
            }
        }while ($tmp);
        
        print_r($data);die;
    }
    
    public function caca($data) {
        
    }
}
