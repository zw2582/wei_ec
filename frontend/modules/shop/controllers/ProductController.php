<?php
namespace frontend\modules\shop\controllers;

use common\controllers\BasicController;
use frontend\models\ProductSearch;
use yii\base\UserException;
use yii\data\Pagination;

/**
 * Default controller for the `shop` module
 */
class ProductController extends BasicController {
    
    /**
     * 店铺首页
     * 
     * wei.w.zhou@integle.com
     * 2017年12月8日下午7:27:18
     */
    public function actionIndex() {
        $supplierId = \Yii::$app->get('supplier_id', DEFAULT_SUPPID);
        
        \Yii::trace(sprintf('获取鹰群：%d的店招产品', $supplierId), __METHOD__);
        $products = ProductSearch::homeIntro($supplierId);
        
        return $this->render('index', [
            'products' => $products
        ]);
    }
    
    /**
     * 产品搜索
     * @throws UserException
     * @return string
     * wei.w.zhou@integle.com
     * 2017年12月9日下午3:29:51
     */
    public function actionSearch() {
        $keyword = \Yii::$app->request->get('keyword');
        $supplierId = \Yii::$app->get('supplier_id', DEFAULT_SUPPID);
        
        if (empty($keyword)) {
            throw new UserException('请传递keyword');
        }
        
        $pager = new Pagination(['defaultPageSize'=>10]);
        $search = new ProductSearch();
        $search->keyword = $keyword;
        $products = $search->search($supplierId, $pager);
        
        return $this->render('pro_list',[
            'products' => $products
        ]);
    }
    
    /**
     * 产品详情
     * 
     * wei.w.zhou@integle.com
     * 2017年12月9日下午3:41:09
     */
    public function actionDetail() {
        
    }
}
