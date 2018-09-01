<?php
namespace frontend\models;

use yii\base\Component;
use yii\base\UserException;
use linslin\yii2\curl\Curl;
use common\models\User;

/**
 * 微信认证校验
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月5日下午2:09:27
 */
class WeiAuthor extends Component{
    
    public $codeUri = '';
    
    public function init() {
        parent::init();
        if (empty($this->codeUri)) {
            throw new UserException('lose required params codeUri');
        }
    }
    
    /**
     * 调用微信接口获取用户信息
     * 
     * @author zhouwei@shzhanmeng.com
     * @copyright 2018年9月1日 上午10:40:30
     */
    public function findWeixinUserInfo() {
        //判断用户获取地址是否存在
        $getInfoUrl = \Yii::$app->request->get("getinfo_url");
        if($getInfoUrl){
            //存在则表示已经拿到微信用户信息
            $wxUser = file_get_contents($getInfoUrl);
            $wxUserInfo = json_decode($wxUser, true);
            
            return $wxUserInfo;
        } else {
            //设置回调地址
            $backUrl = \Yii::$app->request->hostInfo.'/'.\Yii::$app->request->pathInfo;
            
            $link= $this->codeUri."?getback_url=".urlencode($backUrl);
            \Yii::info('跳转链接获取code的请求:'.$link, __METHOD__);
            \Yii::$app->response->redirect($link);
            die;
        }
    }
    
    //登录
    public function login($test = false) {
        if (!\Yii::$app->user->isGuest) {
            \Yii::info('用户已经登录', __METHOD__);
            return true;
        }
        if ($test) {
            return $this->loginTest();
        }
        
        //获取微信信息
        $userInfo = $this->findWeixinUserInfo();
        if (!$userInfo) {
            \Yii::error('获取微信用户信息返回null', __METHOD__);
            return false;
        }
        \Yii::info('获取微信用户信息返回：'.json_encode($userInfo), __METHOD__);
        $user = User::findOne(['openid'=>$userInfo['openid'], 'status'=>User::STATUS_ACTIVE]);
        if (!$user) {
            \Yii::info('新增微信用户', __METHOD__);
            $user = new User();
            $user->auth_key = '';
            $user->password_hash = '';
            $user->email = '';
            $user->created_at = time();
            $user->updated_at = time();
            $user->username = $userInfo['nickname'];
            $user->openid = $userInfo['openid'];
            $user->sex = $userInfo['sex'];
            $user->province = $userInfo['province'];
            $user->city = $userInfo['city'];
            $user->country = $userInfo['country'];
            $user->headimgurl = $userInfo['headimgurl'];
            $user->privilege = json_encode($userInfo['privilege']);
            //$user->unionid = $userInfo['unionid'];
            if (!$user->save()) {
                throw new UserException(current($user->getFirstErrors()));
            }
        }
        
        return \Yii::$app->user->login($user);
    }
    
    public function loginTest() {
        $user = new User();
        $user->username = 'test';
        $user->sex = 1;
        $user->id = 1;
        $user->headimgurl = 'http://img.mp.itc.cn/upload/20170801/afc9309df32944129d0820121bd64c9e_th.jpg';
        
        return \Yii::$app->user->login($user);
    }
}
