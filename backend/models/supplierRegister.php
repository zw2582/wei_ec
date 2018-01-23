<?php
namespace backend\models;

use common\models\Supplier;

class supplierRegister extends Supplier{
    
    /**
     * !用户取消属性的安全性
     * {@inheritDoc}
     * @see \common\models\Supplier::rules()
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'address', '!user_id'], 'required'],
            [['audit_status', 'user_id', 'status'], 'integer'],
            [['!create_time'], 'safe'],
            [['name', 'address'], 'string', 'max' => 255],
            [['tag', 'province', 'city', 'telephone', 'phone', 'email'], 'string', 'max' => 32],
        ];
    }
}

