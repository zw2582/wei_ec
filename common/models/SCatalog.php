<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_catalog".
 *
 * @property string $id
 * @property string $supplier_id
 * @property string $name
 * @property string $parent_id
 * @property integer $user_id
 * @property string $create_time
 * @property integer $order
 */
class SCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'name', 'user_id'], 'required'],
            [['supplier_id', 'parent_id', 'user_id', 'order'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
            'order' => 'Order',
        ];
    }
}
