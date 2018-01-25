<?php
namespace picture\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

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
     * @example http://dev.picture.integle.com/site/index?src=0002_50_50_1.jpg
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
        if (!preg_match('/[\w_]+\.\w+/', $src)) {
            $this->sendDefault();
        }
        //解析src
        $srcinfo = explode($this->splitTag, $src);
        $leninfo = count($srcinfo);
        
        if ($leninfo < 2) {
            \Yii::error('参数太少', __METHOD__);
            $this->sendDefault();
        }
        //获取图片名称
        $imgname = preg_filter("/^\d+".$this->splitTag.'/', '', $src);
        //获取图片扩展
        $ext = explode('.', $srcinfo[$leninfo-1])[1];
        $despath = PIC_DIR.'/'.$srcinfo[0].'/'.$imgname;
        
        if (file_exists($despath)) {
            \Yii::trace('文件已存在直接返回', __METHOD__);
            header("Content-Type: image/".$ext);
            $handle = fopen($despath, 'rb');
            fpassthru($handle);die;
        } else {
            //获取原图
            $ramImgPath = PIC_DIR.'/'.$srcinfo[0].'/'.$srcinfo[$leninfo - 1];
            if (!file_exists($ramImgPath)) {
                \Yii::trace('源文件不存在，返回默认图片', __METHOD__);
                $this->sendDefault();
            }
            $image = \Yii::$app->image->load($ramImgPath);
            //判断图片需要缩放还是切割
            if ($leninfo == 3) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[1]?:NULL);
            } else if ($leninfo == 4) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[1]?:NULL, $srcinfo[2]?:NULL);
            }   else if($leninfo == 5) {
                //执行缩放操作
                \Yii::trace('执行缩放操作', __METHOD__);
                $image->resize($srcinfo[1]?:NULL, $srcinfo[2]?:NULL, $srcinfo[3]?:NULL);
            } elseif ($leninfo == 6) {
                //执行切割操作
                \Yii::trace('执行切割操作', __METHOD__);
                $image->crop($srcinfo[1], $srcinfo[2], $srcinfo[3]?:NULL, $srcinfo[4]?:NULL);
            } elseif($leninfo == 7) {
                //执行先切割再缩放
                \Yii::trace('执行切割操作再缩放操作', __METHOD__);
                $image->crop($srcinfo[1], $srcinfo[2], $srcinfo[3]?:NULL, $srcinfo[4]?:NULL);
                $image->resize($srcinfo[5]?:NULL);
            }elseif ($leninfo == 8) {
                //执行先切割再缩放
                \Yii::trace('执行切割操作再缩放操作', __METHOD__);
                $image->crop($srcinfo[1], $srcinfo[2], $srcinfo[3]?:NULL, $srcinfo[4]?:NULL);
                $image->resize($srcinfo[5]?:NULL, $srcinfo[6]?:NULL);
            }
            $image->save($despath);
            header("Content-Type: image/".$ext);
            echo $image->render(); die;
        }
    }
    
    private function sendDefault() {
        header("Content-Type: image/png");
        $handle = fopen($this->default_imgpath, 'rb');
        fpassthru($handle);die;
    }
    
}
