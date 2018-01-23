<?php
namespace backend\modules\product\controllers;

use backend\controllers\AuthController;
use common\models\SProduct;
use yii\data\Pagination;
use backend\modules\product\models\ProductForm;
use backend\modules\product\models\ProductSearch;

/**
 * 商品控制器
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月22日下午3:09:13
 */
class ProductController extends AuthController{
    
    /**
     * 查看商品列表
     * 
     * wei.w.zhou@integle.com
     * 2018年1月22日下午3:42:06
     */
    public function actionList() {
        $pager = new Pagination(['pageSizeParam'=>'size']);
        
        $productSearch = new ProductSearch();
        
        $data = $productSearch->search($this->supplierId, $pager);
        
        return $this->ajaxSuccess([
            'total'=>$pager->totalCount,
            'data'=>$data
        ]);
    }
    
    /**
     * 新增
     * 
     * <pre>
     * {
     *  name:xxx,
     *  number:xxx,
     *  description:xxx,
     *  packages:[{
     *       &nbsp;&nbsp;package:xxx,
     *       &nbsp;&nbsp;package_type:xxx,
     *       &nbsp;&nbsp;price:xxx,
     *       &nbsp;&nbsp;count:xxx
     *      },{
     *      &nbsp;&nbsp;...
     *  }]
     * </pre>
     * wei.w.zhou@integle.com
     * 2018年1月23日下午3:16:37
     */
    public function actionAdd() {
        $productForm = new ProductForm();
        
        $productForm->setAttributes(\Yii::$app->request->post());
        
        if (!$productForm->save($this->supplierId, $this->userId)) {
            return $this->ajaxFail(current($productForm->getFirstErrors()));
        }
        
        return $this->ajaxSuccess(null, '新增成功');
    }
}

