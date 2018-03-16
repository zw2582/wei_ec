<?php
namespace paoma\models;

use yii\base\Model;

class PaomaUser extends Model{
    
    //用户id
    public $uid;
    
    //名称
    public $uname;
    
    //头像地址
    public $headimg;
    
    //用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
    public $sex;
    
    //当前房间号
    public $room_no;
    
    const prefix = 'paoma_user_';
    
    /**
     * 保存user到redis
     * @param array $data
     * wei.w.zhou@integle.com
     * 2018年3月7日下午1:57:55
     */
    public static function saveUser($uid, $data) {
        $redis = \Yii::$app->redis;
        
        $redis->hset(self::prefix.$uid, 'uid', $uid);
        isset($data['uname']) && $redis->hset(self::prefix.$uid, 'uname', $data['uname']);
        isset($data['headimg']) && $redis->hset(self::prefix.$uid, 'headimg', $data['headimg']);
        isset($data['sex']) && $redis->hset(self::prefix.$uid, 'sex', $data['sex']);
        isset($data['room_no']) && $redis->hset(self::prefix.$uid, 'room_no', $data['room_no']);
    }
    
    /**
     * 从redis获取user
     * @param unknown $uid
     * @param string $asArray
     * @return NULL[]|mixed[]|\paoma\models\PaomaUser
     * wei.w.zhou@integle.com
     * 2018年3月7日下午1:58:04
     */
    public static function getUser($uid, $asArray=TRUE) {
        $redis = \Yii::$app->redis;
        
        $data = [
            'uid'=>$redis->hget(self::prefix.$uid, 'uid'),
            'uname'=>$redis->hget(self::prefix.$uid, 'uname'),
            'headimg'=>$redis->hget(self::prefix.$uid, 'headimg'),
            'sex'=>$redis->hget(self::prefix.$uid, 'sex'),
            'room_no'=>$redis->hget(self::prefix.$uid, 'room_no'),
        ];
        if ($asArray) {
            return $data;
        }
        $user = new self();
        $user->setAttributes($data, false);
        return $user;
    }
    
}

