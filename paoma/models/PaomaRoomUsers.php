<?php
namespace paoma\models;

use yii\base\Model;

/**
 * 房间中的用户
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午5:35:18
 */
class PaomaRoomUsers extends Model{
    
    /*
     * 存储房间内的用户，set集合类型
     */
    const prefix = 'paoma_room_users_';
    
    /**
     * 增加用户uuid
     * @param string $roomNo
     * @param string $uuid
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:36:17
     */
    public static function add($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        $redis->sadd(self::prefix.$roomNo, $uuid);
    }
    
    /**
     * 返回房间内的所有用户的uuid
     * @param unknown $roomNo
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午10:35:37
     */
    public static function members($roomNo) {
        $redis = \Yii::$app->redis;
        
        return $redis->smembers(self::prefix.$roomNo);
    }
    
    public static function exist($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        return $redis->sismember(self::prefix.$roomNo, $uuid);
    }
    
    /**
     * 退出房间
     * @param string $roomNo
     * @param string $uuid
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:39:17
     */
    public static function exitRoom($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        $redis->srem(self::prefix.$roomNo, $uuid);
    }
    
    /**
     * 返回当前房间人员总数
     * @param unknown $roomNo
     * wei.w.zhou@integle.com
     * 2018年2月12日上午10:55:20
     */
    public static function count($roomNo) {
        $redis = \Yii::$app->redis;
        
        return $redis->scard(self::prefix.$roomNo);
    }
}

