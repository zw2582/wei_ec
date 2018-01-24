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
    
    /**
     * 递归获取当前类型id的所有父类型id数组,如果当前类型不存在，则返回空数组
     * @param unknown $catalogId
     * @return \common\models\SCatalog[]
     * wei.w.zhou@integle.com
     * 2018年1月24日上午11:05:30
     */
    public static function findRecursiveId($catalogId) {
        $catalog = self::findOne([$catalogId]);
        
        $arr = [];
        if ($catalog) {
            do {
                $arr[] = $catalog['id'];
                $catalog = self::findOne(['id'=>$catalog['parent_id']]);
            } while($catalog);
        }
        return $arr;
    }
}
