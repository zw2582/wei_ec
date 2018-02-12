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
    
    public $appid;

    public $appsecret='f87da08f9e619ab9b95205d2f17d8dfc';
    
    public function init() {
        parent::init();
        if (empty($this->appid)) {
            throw new UserException('lose required params');
        }
    }
    
    //用户同意授权，获取code
    public function getCode($scope) {
        if (!in_array($scope, ['snsapi_userinfo', 'snsapi_base'])) {
            throw new UserException('获取code的scope请在【snsapi_userinfo，snsapi_base】中选择');
        }
        $code = \Yii::$app->request->get("code");
        if (is_null($code)) {
            $redirectUri = \Yii::$app->request->absoluteUrl;
//             $redirectUri = preg_replace('/http:/', 'https:', $redirectUri);
            //$authlink = 'https://open.weixin.qq.com/connect/oauth2/authorize';
            $authlink='https://nbfq.site/weiauth.php';
            $link = sprintf('%s?appid=%s&response_type=code&scope=%s&state=%s&to=weixin#wechat_redirect',
                $authlink, $this->appid, $scope, urlencode($redirectUri).'|'.$scope);
            
            \Yii::info('跳转链接获取code的请求:'.$link, __METHOD__);
            \Yii::$app->response->redirect($link);
            return [null,null];
        }
        $state = \Yii::$app->request->get("state");
        $state = explode('|', $state)[1];
        \Yii::info('获取到微信认证code：'.$code, __METHOD__);
        return [$code, $state];
    }
    
    //获取access_token
    public function getAccessToken($code) {
        $link = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $link = sprintf($link, $this->appid, $this->appsecret, $code);
        
        \Yii::info('获取accessToken网页授权:'.$link);
        $curl = new Curl();
        $response = $curl->setOptions([
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_SSL_VERIFYHOST=>0
        ])->get($link);
        \Yii::info('获取accessToken网页授权response:'.$response);
        if ($curl->responseCode == 200) {
	    $response = json_decode($response, true);
            return [$response['access_token'], $response['openid']];
        } else {
            \Yii::error('获取accessToken网页授权失败:'.$response, __METHOD__);
        }
    }
    
    //根据access_token获取用户信息
    public function getUserInfoByAccessToken($accessToken, $openId) {
        if (empty($accessToken) || empty($openId)) {
            return null;
        }
        
        $link = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
        $link = sprintf($link, $accessToken, $openId);
        \Yii::info(sprintf('根据access_token获取用户信息:%s', $link), __METHOD__);
        $curl = new Curl();
        $response = $curl->setOptions([
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_SSL_VERIFYHOST=>0
        ])->get($link);
        \Yii::info(sprintf('根据access_token获取用户信息response:%s', $response), __METHOD__);
        if ($curl->responseCode != 200) {
            throw new UserException('据access_token获取用户信息失败:'.$response);
        }
        if (isset($response['errcode'])) {
            throw new UserException('据access_token获取用户信息失败:'.$response['errmsg']);
        }
        return json_decode($response,true);
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
        //1.snsapi_base获取openid
        list($code, $state) = $this->getCode('snsapi_base');
        if (empty($code) || empty($state)) {
            \Yii::info('没有获取到code，state，可能是发生微信跳转，等待回执请求', __METHOD__);
            return false;
        }
        list($accessToken, $openId) = $this->getAccessToken($code);
        if ($state == 'snsapi_base') {
            \Yii::info('接收到snsapi_base回执，判断是否存在user', __METHOD__);
            $user = User::findOne(['openid'=>$openId, 'status'=>User::STATUS_ACTIVE]);
            if (empty($user)) {
                \Yii::info('snsapi_base判断用户不存在，启动snsapi_userinfo认证', __METHOD__);
		unset($_GET['code']);
                $this->getCode('snsapi_userinfo');
                return false;
            }
        } elseif ($state == 'snsapi_userinfo') {
            \Yii::info('接收到snsapi_base回执，获取用户信息', __METHOD__);
            $userInfo = $this->getUserInfoByAccessToken($accessToken, $openId);
            if (!$userInfo) {
                \Yii::error('根据access_token获取用户信息返回null', __METHOD__);
                return false;
            }
            \Yii::info('根据access_token获取用户信息返回：'.json_encode($userInfo), __METHOD__);
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
