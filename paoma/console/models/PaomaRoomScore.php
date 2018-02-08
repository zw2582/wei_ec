<?php
namespace paoma\console\models;

use yii\base\Model;
use paoma\models\PaomaUserRoom;
use paoma\models\PaomaRoomUsers;

/**
 * 跑马分值
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月8日上午9:56:36
 */
class PaomaRoomScore extends Model{
    
    const prefix="paoma_room_score_";
    
    const overkey = "paoma_room_over_";
    
    const maxtime = 10;
    
    /**
     * 设置房间开始
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:14:47
     */
    public static function begin() {
        $redis = \Yii::$app->redis;
        
        //设置结束变量
        return $redis->set(self::overkey.$roomNo, "go play", "ex".self::maxtime, "nx");
    }
    
    /**
     * 获取当前房间比赛状态
     * @param unknown $roomNo
     * @return boolean true:比赛中，false:比赛结束
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:43:08
     */
    public static function status($roomNo) {
        $redis = \Yii::$app->redis;
        
        return $redis->get(self::overkey.$roomNo) ? true : false;
    }
    
    /**
     * 新增
     * @param int $roomNo
     * @param string $uuid
     * @param int $count
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午10:19:49
     */
    public static function add($roomNo, $uuid, $count) {
        $redis = \Yii::$app->redis;
        
        if (!$redis->exists(self::overkey.$roomNo)) {
            \Yii::info('时间到', 'room_score');
            return false;
        }
        
        //新增分值
        return $redis->zincrby(self::prefix.$roomNo, $count, $uuid);
    }
    
    /**
     * 按排名显示分值数据
     * @param unknown $roomNo
     * @param number $top
     * @param number $end
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:18:41
     */
    public static function listTop($roomNo, $top=0, $end=10) {
        $redis = \Yii::$app->redis;
        
        return $redis->zrange(self::prefix.$roomNo, $top, $end, true);
    }
    
    /**
     * 返回当前用户排名的前4后5的用户
     * @param unknown $roomNo
     * @param unknown $uuid
     * @param number $range
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:24:11
     */
    public static function listByUUid($roomNo, $uuid, $range=10) {
        $redis = \Yii::$app->redis;
        
        $rank = $redis->zrank(self::prefix.$roomNo, $uuid);
        
        return $redis->zrange(self::prefix.$roomNo, $rank-4, $rank+5, true);
    }
    
    
}

