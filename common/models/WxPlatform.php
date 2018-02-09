<?php
namespace common\models;

use yii\base\Model;
use linslin\yii2\curl\Curl;
use yii\base\UserException;

/**
 * 微信平台
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月29日下午1:47:29
 */
class WxPlatform extends Model{
    
    //微信服务接口地址
    const wxurl = 'https://api.weixin.qq.com';
    
    /**
     * 使用登录凭证 code 获取 session_key 和 openid
     * @param string $appId
     * @param string $secret
     * @param string $code
     * @throws UserException
     * @return mixed
     * wei.w.zhou@integle.com
     * 2017年12月29日下午1:56:13
     */
    public static function getJscode2session($appId, $secret, $code) {
        \Yii::info('使用登录凭证 code 获取 session_key 和 openid', __METHOD__);
        $url = self::wxurl.'/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        
        $curl = new Curl();
        $response = $curl->setOptions([
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_SSL_VERIFYHOST=>0
        ])->get(sprintf($url, $appId, $secret, $code));
        
        if ($curl->errorCode !== NULL) {
            \Yii::error($curl->errorText, __METHOD__);
            throw new UserException($curl->errorText);
        }
        if ($curl->responseCode != '200') {
            \Yii::error('httpcode'.$curl->responseCode.',reponse:'.$response, __METHOD__);
            throw new UserException($response);
        }
        $jsonRes = json_decode($response, TRUE);
        if (empty($jsonRes['openid'])) {
            \Yii::error($response, __METHOD__);
            throw new UserException($response);
        }
        return $jsonRes;
    }
    
    /**
     * 获取access_token<br/>
     * access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token。开发者需要进行妥善保存。
     * access_token的存储至少要保留512个字符空间。access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
     * @param string $appId 第三方用户唯一凭证
     * @param string $secret 第三方用户唯一凭证密钥，即appsecret
     * @param string $grantType 获取access_token填写client_credential
     * wei.w.zhou@integle.com
     * 2018年1月29日下午4:04:00
     */
    public static function getAccessToken($appId, $secret, $grantType='client_credential') {
        $curl = new Curl();
        
        $url = sprintf(self::wxurl.'/cgi-bin/token?grant_type=%s&appid=%s&secret=%s', $grantType, $appId, $secret);
        $response = $curl->setOptions([
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_SSL_VERIFYHOST=>0
        ])->get($url);
        
        \Yii::info('获取access_token reponse:'.$response);
        if ($curl->errorCode !== NULL) {
            \Yii::error($curl->errorText, __METHOD__);
            throw new UserException($curl->errorText);
        }
        if ($curl->responseCode != '200') {
            \Yii::error('httpcode'.$curl->responseCode.',reponse:'.$response, __METHOD__);
            throw new UserException($response);
        }
        $jsonRes = json_decode($response, TRUE);
        if (isset($jsonRes['errcode'])) {
            switch ($jsonRes['errcode']) {
                case -1:
                    throw new UserException('系统繁忙，此时请开发者稍候再试');break;
                case 40001:
                    throw new UserException('AppSecret错误或者AppSecret不属于这个公众号，请开发者确认AppSecret的正确性');break;
                case 40002:
                    throw new UserException('请确保grant_type字段值为client_credential');break;
                case 40164:
                    throw new UserException('调用接口的IP地址不在白名单中，请在接口IP白名单中进行设置');
            }
        }
        return $jsonRes;
    }
}

