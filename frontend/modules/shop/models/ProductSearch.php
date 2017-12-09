<?php
namespace frontend\models;

use yii\base\Model;
use common\models\SProduct;
use yii\data\Pagination;

/**
 * 产品搜索
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月8日下午7:38:42
 */
class ProductSearch extends Model{
    
    public $keyword;
    
    /**
     * 店铺首页简介产品
     */
    public static function homeIntro($supplierId) {
        return SProduct::find()->andWhere([
            'supplier_id'=>$supplierId,
            'status'=>1
        ])->limit(4)->all();
    }
    
    /**
     * 产品搜索
     * @param unknown $supplierId
     * @param Pagination $pager
     * @return array|\yii\db\ActiveRecord[]
     * wei.w.zhou@integle.com
     * 2017年12月8日下午7:44:49
     */
    public function search($supplierId, Pagination $pager) {
        $query = SProduct::find()->andWhere([
            'supplier_id'=>$supplierId,
            'status'=>1
        ]);
        $query->andFilterWhere(['like','name', $this->keyword]);
        
        if ($pager instanceof Pagination) {
            $pager->totalCount = $query->count();
            $query->offset($pager->offset)->limit($pager->limit);
        }
        
        return $query->all();
    }
}

