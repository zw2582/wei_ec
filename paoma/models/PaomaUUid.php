<?php
namespace paoma\models;

use yii\base\Model;

class PaomaUUid extends Model{
    
    const prefix = 'paoma_uuid_';
    const uuiduid = 'paoma_uid_';
    
    /**
     * 根据用户id获取uuid
     * @param int $uId 用户id
     * @return string uuid
     * wei.w.zhou@integle.com
     * 2018年2月5日上午10:14:09
     */
    public static function getByUid($uId) {
        $redis = \Yii::$app->redis;
        return $redis->get(self::prefix.$uId);
    }
    
    public static function getUidByUUid($uuid) {
        $redis = \Yii::$app->redis;
        return $redis->get(self::uuiduid.$uuid);
    }
    
    /**
     * 设置用户的uuid
     * @param int $uId 用户id
     * @param string $uuid uuid
     * @return unknown
     * wei.w.zhou@integle.com
     * 2018年2月5日上午10:14:49
     */
    public static function setByUid($uId, $uuid) {
        $redis = \Yii::$app->redis;
        
        $redis->set(self::uuiduid.$uuid, $uId);
        return $redis->set(self::prefix.$uId, $uuid);
    }
}

