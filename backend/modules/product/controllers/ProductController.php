<?php
namespace backend\modules\product\controllers;

use backend\controllers\AuthController;
use common\models\SProduct;
use yii\data\Pagination;
use backend\modules\product\models\ProductForm;
use backend\modules\product\models\ProductSearch;
use yii\web\UploadedFile;
use yii\validators\FileValidator;
use backend\modules\product\models\ProductImageForm;
use common\models\SPackageType;

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
     *  catalog_id:xxx,
     *  images:[{
     *  &nbsp;&nbsp;save_path:xxx,
     *  &nbsp;&nbsp;save_name:xxx,
     *  &nbsp;&nbsp;real_name:xxx,
     *  }],
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
    
    /**
     * 图片保存
     * 
     * wei.w.zhou@integle.com
     * 2018年1月24日下午1:46:29
     */
    public function actionImageUpload() {
        $proImgForm = new ProductImageForm();
        $proImgForm->file = UploadedFile::getInstanceByName('file');
        if (empty($proImgForm->file)) {
            return $this->ajaxFail('no file uploaded');
        }
        if (!$proImgForm->validate(['file'])) {
            return $this->ajaxFail(current($proImgForm->getFirstErrors()));
        }
        //组建地址
        $deep_path = sprintf("%04d", time() % 100);
        $filename = uniqid().'.'.$proImgForm->file->extension;
        $pathname = PIC_DIR.DS.$deep_path;
        if (!is_dir($pathname)) {
            if (!@mkdir($pathname, 775, true)) {
                return $this->ajaxFail('创建文件夹失败');
            }
        }
        \Yii::info("saveas:".$pathname.DS.$filename);
        if (!$proImgForm->file->saveAs($pathname.DS.$filename)) {
            return $this->ajaxFail('上传失败');
        }
        return $this->ajaxSuccess([
            'deep_path'=>$deep_path,
            'save_name'=>$filename,
            'real_name'=>$proImgForm->file->name,
            'pic_url'=>PIC_URL
        ], '上传成功');
    }
    
    /**
     * 列出规格类型
     * 
     * wei.w.zhou@integle.com
     * 2018年1月25日下午1:53:52
     */
    public function actionListPackageType() {
        $names = SPackageType::find()->select('name')
        ->where(['user_id'=>$this->userId])->asArray()->column();
        
        return $this->ajaxSuccess($names);
    }
}

