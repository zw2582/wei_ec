<?php
namespace picture\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\FileHelper;

/**
 * Site controller
 */
class SiteController extends Controller {
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    private $default_imgpath = 'images/4.jpg';
    
    private $splitTag = '_';
    
    /**
     * 获取图片原图
     * 
     * @example http://dev.picture.integle.com/0002/80_80_1.jpg
     * 0002:子路径，50_50_1.jpg为文件名
     * @param src 0002_10_20_1.jpg
     * wei.w.zhou@integle.com
     * 2017年12月8日下午8:14:23
     */
    public function actionIndex() {
        $src = \Yii::$app->request->get('src');
        
        if (empty($src)) {
            \Yii::error('未传递src参数', __METHOD__);
            $this->sendDefault();
        }
        
        //过滤图片路径
        $src = preg_replace('(\.\.\/|\.\/|^\/)', '', $src);
        //获取图片地址
        $despath = PIC_DIR.'/'.$src;
        
        $mimeType = FileHelper::getMimeTypeByExtension($despath);
        if (file_exists($despath)) {
            \Yii::trace('文件已存在直接返回', __METHOD__);
            header("Content-Type: $mimeType");
            $handle = fopen($despath, 'rb');
            fpassthru($handle);die;
        } else {
            if (!preg_match('/(\d+_)+/', $src)) {
                \Yii::error('不需要裁剪', __METHOD__);
                $this->sendDefault();
            } 
            //获取原图
            $imgName = basename($src);
            $srcinfo = explode($this->splitTag, $imgName);
            $ramImgPath = preg_replace('/(\d+_)+/', '', $despath);
            if (!file_exists($ramImgPath)) {
                \Yii::trace('源文件不存在，返回默认图片', __METHOD__);
                $this->sendDefault();
            }
            $image = \Yii::$app->image->load($ramImgPath);
            $leninfo = count($srcinfo) - 1; //减掉图片占用的位置
            //判断图片需要缩放还是切割
            if ($leninfo == 1) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[0]?:NULL);
            } else if ($leninfo == 2) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[0]?:NULL, $srcinfo[1]?:NULL);
            }   else if($leninfo == 3) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[0]?:NULL, $srcinfo[1]?:NULL, $srcinfo[2]?:NULL);
            } elseif ($leninfo == 4) {
                //执行切割操作
                \Yii::trace('执行切割操作', __METHOD__);
                $image->crop($srcinfo[0], $srcinfo[1], $srcinfo[2]?:NULL, $srcinfo[3]?:NULL);
            } elseif($leninfo == 5) {
                //执行先切割再缩放
                \Yii::trace('执行切割操作再缩放操作', __METHOD__);
                $image->crop($srcinfo[0], $srcinfo[1], $srcinfo[2]?:NULL, $srcinfo[3]?:NULL);
                $image->resize($srcinfo[4]?:NULL);
            }elseif ($leninfo == 6) {
                //执行先切割再缩放
                \Yii::trace('执行切割操作再缩放操作', __METHOD__);
                $image->crop($srcinfo[0], $srcinfo[1], $srcinfo[2]?:NULL, $srcinfo[3]?:NULL);
                $image->resize($srcinfo[4]?:NULL, $srcinfo[5]?:NULL);
            }
            $image->save($despath);
            header("Content-Type: $mimeType");
            echo $image->render(); die;
        }
    }
    
    private function sendDefault() {
        header("Content-Type: image/png");
        $handle = fopen($this->default_imgpath, 'rb');
        fpassthru($handle);die;
    }
    
}
