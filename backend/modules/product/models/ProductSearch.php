<?php
namespace backend\modules\product\models;

use yii\base\Model;
use yii\data\Pagination;
use common\models\SProduct;
use common\models\User;
use common\models\SProductImg;

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
        ->innerJoin(User::tableName().' u', 't.user_id=u.id')
        ->leftJoin(SProductImg::tableName().' img', 'img.product_id=t.id');
        
        //查询列
        $query->select('t.*,u.username,img.save_path,img.save_name');
        
        $query->groupBy('t.id');
        
        if ($pager instanceof Pagination) {
            $pager->totalCount = $query->count();
            $query->offset($pager->offset)->limit($pager->limit);
        }
        
        return $query->asArray()->all();
    }
}

