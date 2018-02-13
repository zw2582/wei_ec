<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaUserRoom;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUser;

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
    public static function begin($roomNo) {
        $redis = \Yii::$app->redis;
        
        //设置结束变量
        return $redis->set(self::overkey.$roomNo, "play", "ex", self::maxtime, "nx");
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
        
        $data = $redis->zrevrangebyscore(self::prefix.$roomNo, 1000, 0, 'withscores', 'limit', $top, $end);
        
        $result = [];
        foreach ($data as $k=>$v) {
            if ($k % 2 == 0) {
                $result[$v] = [
                    'uuid'=>$v,
                    'score'=>$data[$k+1],
                    'user'=>PaomaUser::getUser($v),
                    'rank'=>$k+1
                ];
            }
        }
        return $result;
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
        
        //放回当前用户的分值
        $score = $redis->zscore(self::prefix.$roomNo, $uuid);
        $rank = $redis->zrank(self::prefix.$roomNo, $uuid);
        
        if (empty($score)) {
            //如果用户不在比赛中也返回前十名
            return self::listTop($roomNo);
        }
        
        //获取前10名
        $data = $redis->zrevrangebyscore(self::prefix.$roomNo, 1000, 0, 'withscores', 'limit', 0, 10);
        $result = [];
        foreach ($data as $k=>$v) {
            if ($k % 2 == 0) {
                $result[$v] = [
                    'uuid'=>$v,
                    'score'=>$data[$k+1],
                    'user'=>PaomaUser::getUser($v),
                    'rank'=>$k+1
                ];
            }
        }
        
        if (count($result) > 5) {
            //如果当前uuid在数组中，则移除当前元素数据，如果不在则，移除最后一位数据
            if (array_key_exists($uuid, $result)) {
                unset($result['uuid']);
            } else {
                array_pop($result);
            }
            
            //将当前用户数据插入第四位
            $result = array_splice($result, 3, 0, [$uuid=>[
                'uuid'=>$uuid,
                'score'=>$score,
                'user'=>PaomaUser::getUser($uuid),
                'rank'=>$rank
            ]]);
        }
        
        return $result;
    }
    
    /**
     * 退出比赛
     * @param unknown $roomNo
     * @param unknown $uuid
     * wei.w.zhou@integle.com
     * 2018年2月11日下午3:01:28
     */
    public static function remove($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        return $redis->zrem(self::prefix.$roomNo, $roomNo);
    }
    
    /**
     * 清除比赛结果
     * @param unknown $roomNo
     * wei.w.zhou@integle.com
     * 2018年2月12日上午9:46:27
     */
    public static function clear($roomNo) {
        $redis = \Yii::$app->redis;
        
        $redis->del(self::prefix.$roomNo);
    }
    
    
}

