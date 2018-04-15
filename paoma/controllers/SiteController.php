<?php
namespace paoma\controllers;

use yii\web\Controller;
use common\models\User;
use dosamigos\qrcode\QrCode;
use yii\helpers\Url;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUser;

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
        $this->redirect('/web.html');
    }
    
    /**
     * 手机端首页的唯一入口跑马首页
     * @return string
     * wei.w.zhou@integle.com
     * 2018年2月9日下午3:30:44
     */
    public function actionPhone() {
        $roomNo = \Yii::$app->request->get('room_no');
        $uuid = \Yii::$app->request->get('uuid');
        
        if (\Yii::$app->user->isGuest) {
            //缓存这俩参数，接下来要header跳转了
            \Yii::$app->session->set('room_no', $roomNo);
            \Yii::$app->session->set('uuid', $uuid);
            if(!\Yii::$app->weiauthor->login()) {
                return;
            }
        } 
        $redisUser = PaomaUser::getUser(\Yii::$app->user->id);
        if (empty($redisUser)) {
            //登录之后保存用户信息到redis中
            $user = \Yii::$app->user->identity;
            PaomaUser::saveUser($user->id, [
                'headimg'=>$user->headimgurl,
                'sex'=>$user->sex,
                'uname'=>$user->username
            ]);
        }
        //获取上面俩参数
        $roomNo = \Yii::$app->session->get('room_no');
        $uuid = \Yii::$app->session->get('uuid');
        
        //重定向跳转
        $url[] = '/index.html';
        $roomNo && $url['room_no'] = $roomNo;
        $uuid && $url['uuid'] = $uuid;
        $this->redirect($url);
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
    
    public function actionTest() {
        PaomaUser::saveUser(1, ['room_no'=>0]);
        printf("退出房间失败：%s\n", 'sdfs');
    }
}
