<?php
namespace paoma\models;

/**
 * 房间fd连接值，暂时只存放游客的，非游客的存储在swoole_table中
 * @author zhangjiao
 *
 */
class PaomaRoomFd {
    
    /*
     * 存储房间内的用户，list类型
     */
    const prefix = 'paoma_room_fd_';
    
    /**
     * 增加fd
     * @param int $roomNo 房间id
     * @param int $fd
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:36:17
     */
    public static function add($roomNo, $fd) {
        $redis = \Yii::$app->redis;
        
        $key = self::prefix.$roomNo;
        
        $redis->sadd($key, $fd);
    }
    
    /**
     * 返回房间的fd集合
     * @param unknown $roomNo
     * @return mixed
     */
    public static function members($roomNo) {
        $redis = \Yii::$app->redis;
        
        $key = self::prefix.$roomNo;
        
        return $redis->smembers($key);
    }
    
    public static function del($roomNo, $fd) {
        $redis = \Yii::$app->redis;
        
        $key = self::prefix.$roomNo;
        
        $redis->srem($key);
    }
}

