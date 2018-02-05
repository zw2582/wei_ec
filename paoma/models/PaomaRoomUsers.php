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
     * 退出房间
     * @param string $roomNo
     * @param string $uuid
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:39:17
     */
    public static function exit($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        $redis->srem(self::prefix.$roomNo, $uuid);
    }
}

