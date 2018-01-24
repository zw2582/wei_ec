<?php
namespace backend\modules\product\models;

use yii\base\Model;
use common\models\SProductImg;
use yii\web\UploadedFile;

/**
 * 商品图片管理
 * @author wei.w.zhou@integle.com
 *
 * 2018年1月24日上午10:27:35
 */
class ProductImageForm extends Model{
    
    public $save_path;
    
    public $save_name;
    
    public $real_name;
    
    /**
     * @var UploadedFile 图片文件
     */
    public $file;
    
    public function rules() {
        return [
            [['save_path','save_name','real_name'], 'required'],
            [['file'], 'file', 'extensions'=>'jpg,gif,png', 'maxSize'=>20*1024*1024]
        ];
    }
    
    /**
     * 保存图片
     * 会判断是否已存在save_path和save_name，如果存在则修改real_name，如果不存在则新增图片
     * @param integer $productId
     * @return boolean
     * wei.w.zhou@integle.com
     * 2018年1月24日上午10:34:34
     */
    public function save($productId) {
        \Yii::info('保存商品图片', __METHOD__);
        if (!$this->validate()) {
            \Yii::error('保存商品图片校验参数失败', __METHOD__);
            return false;
        }
        $proImg = SProductImg::findOne(['product_id'=>$productId,
            'save_name'=>$this->save_name,
            'save_path'=>$this->save_path
        ]);
        if (!$proImg) {
            $proImg = new SProductImg(['product_id'=>$productId]);
            $proImg->save_path = $this->save_path;
            $proImg->save_name = $this->save_name;
        }
        $proImg->real_name = $this->real_name;
        
        if (!$proImg->save()) {
            \Yii::error('保存商品图片失败', __METHOD__);
            $this->addErrors($proImg->getErrors());
            return false;
        }
        return true;
    }
}

