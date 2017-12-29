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
}

