<?php
namespace paoma\models;

use yii\base\Model;
use console\modules\paoma\models\PaomaRoomScore;

/**
 * 房间中的用户
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午5:35:18
 */
class PaomaRoomUsers extends Model{
    
    /*
     * 存储房间内的用户，list类型
     */
    const prefix = 'paoma_room_users_';
    
    /**
     * 增加用户uuid
     * @param int $roomNo 房间id
     * @param int $uid 用户id
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:36:17
     */
    public static function add($roomNo, $uid) {
        $redis = \Yii::$app->redis;
        
        $key = self::prefix.$roomNo;
        
        $userids = $redis->lrange($key, 0, -1);
        if (in_array($uid, $userids)) {
            \Yii::info("用户$uid已是房间$roomNo成员", 'paoma_room_users');
            return;
        }
        $redis->rpush($key, $uid);
    }
    
    /**
     * 返回房间内的所有用户的uuid
     * @param int $roomNo
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午10:35:37
     */
    public static function members($roomNo) {
        $redis = \Yii::$app->redis;
        
        return $redis->lrange(self::prefix.$roomNo, 0, -1);
    }
    
    public static function listByUUid($roomNo, $uuid) {
        $redis = \Yii::$app->redis;
        
        $members = $redis->smembers(self::prefix.$roomNo);
        
        $members = array_slice($members, 0, 10);
        $memberList = [];
        foreach ($members as $member) {
            $memberList[$member] = [
                'uuid'=>$member,
                'user'=>PaomaUser::getUser($member),
                'rank'=>0,
                'score'=>0
            ];
        }
        
        if (count($memberList) > 5) {
            if (array_key_exists($uuid, $memberList)) {
                unset($memberList[$uuid]);
            } else {
                array_pop($memberList);
            }
            
            $memberList = array_splice($memberList, 3, 0, [$uuid=>[
                'uuid'=>$uuid,
                'score'=>0,
                'user'=>PaomaUser::getUser($uuid),
                'rank'=>0
            ]]);
        }
        
        return $memberList;
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
        PaomaUserRoom::del($uuid);
        PaomaRoomScore::remove($roomNo, $uuid);
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

