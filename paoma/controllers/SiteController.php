<?php
namespace paoma\controllers;

use yii\web\Controller;
use dosamigos\qrcode\QrCode;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 跑马官网控制台
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 二维码生成
     * @return unknown
     * wei.w.zhou@integle.com
     * 2018年2月5日上午11:50:14
     */
    public function actionQrcode() {
        $str = \Yii::$app->request->get('str', 'http://paoma.com');
        
        $str = urldecode($str);
        
        return QrCode::png($str);
    }
}
