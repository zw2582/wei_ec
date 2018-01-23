<?php
namespace backend\modules\product\models;

use yii\base\Model;
use yii\data\Pagination;
use common\models\SProduct;
use common\models\User;

/**
 * 产品搜索
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月23日下午3:08:20
 */
class ProductSearch extends Model{
    
    public function search($supplierId, Pagination $pager = null) {
        //定义关系
        $query = SProduct::find()->from(SProduct::tableName().' t')
        ->innerJoin(User::tableName().' u', 't.user_id=u.id');
        
        //查询列
        $query->select('t.*,u.username');
        
        if ($pager instanceof Pagination) {
            $pager->totalCount = $query->count();
        }
        
        return $query->asArray()->all();
    }
}

