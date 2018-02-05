<?php
namespace paoma\models;

use yii\base\Model;

/**
 * 用户当前所在房间
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午5:24:05
 */
class PaomaUserRoom extends Model{
    
    /*
     * 存储用户当前所在房间编号
     */
    const prefix = 'paoma_user_roomno_';
    
    /**
     * 获取用户所在房间
     * @param string $uuid
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:25:18
     */
    public static function get($uuid) {
        $redis = \Yii::$app->redis;
        return $redis->get(self::prefix.$uuid);
    }
    
    /**
     * 设置用户所在房间
     * @param string $uuid
     * @param int $roomNo
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:26:57
     */
    public static function set($uuid, $roomNo) {
        $redis = \Yii::$app->redis;
        return $redis->set(self::prefix.$uuid, $roomNo);
    }
    
    /**
     * 删除用户所在房间
     * @param string $uuid
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:26:49
     */
    public static function del($uuid) {
        $redis = \Yii::$app->redis;
        return $redis->del(self::prefix.$uuid);
    }
}

