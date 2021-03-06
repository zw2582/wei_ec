<?php
namespace paoma\models;

use yii\base\Model;
use yii\base\UserException;

/**
 * 房间，redis存储
 * @author wei.w.zhou@integle.com
 * 
 * paoma_room_no结构存储房间的基本信息，hash类型
 * room_no:房间号码
 * uuid：房主uuid,
 * uid:房主用户id
 * uname:房主名称
 * isactive:是否激活，1.准备开赛，等待马入场,2.正在比赛，3.比赛结束
 * online:当前人数
 * count:游戏回合
 *
 * 2018年2月5日上午9:48:01
 */
class PaomaRoom extends Model{
    
    /*
     * 跑马房间
     */
    const prefix = 'paoma_room_';
    
    /*
     * 存储现有的房间号，作为房间号自增主键
     */
    const db_room_no = 'paoma_room_no';
    
    /**
     * 查询房间
     * @param string $roomNo 房间号
     * @return array 房间信息
     * wei.w.zhou@integle.com
     * 2018年2月5日上午10:21:02
     */
    public static function findOne($roomNo) {
        $redis = \Yii::$app->redis;
        $data = $redis->hgetall(self::prefix.$roomNo);
        $result = [];
        foreach ($data as $k=>$v) {
            if ($k % 2 == 0) {
                $result[$v] = $data[$k+1];
            }
        }
        return $result;
    }

    /**
     * 创建房间
     * @param int $uId 用户id
     * wei.w.zhou@integle.com
     * @return string 房间号
     * 2018年2月5日上午10:20:51
     */
    public static function create(PaomaUser $user) {
        $redis = \Yii::$app->redis;
        $roomNo = $redis->incr(self::db_room_no);
        \Yii::info(sprintf("uid:%s 创建房间:%s", $user->uid, $roomNo), __METHOD__);
        
        //房间基本信息
        $redis->hset(self::prefix.$roomNo, 'room_no', $roomNo);
        $redis->hset(self::prefix.$roomNo, 'uid', $user->uid);
        $redis->hset(self::prefix.$roomNo, 'uname', $user->uname);
        $redis->hset(self::prefix.$roomNo, 'isactive', 3);
        
        return $roomNo;
    }
    
    /**
     * 修改房间状态
     * @param integer $status 1.准备开赛，等待马入场，0.未开赛，可能房主还未登录，此时不能加入,2.正在比赛，3.比赛结束
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:31:34
     */
    public static function updateStatus($roomNo, $status) {
        if (!in_array($status, [0,1,2,3])) {
            throw new UserException('参数不合法');
        }
        $redis = \Yii::$app->redis;
        $redis->hset(self::prefix.$roomNo, 'isactive', $status);
    }
    
    /**
     * 增加游戏回合数
     * @param unknown $roomNo
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:47:44
     */
    public static function incrCount($roomNo) {
        $redis = \Yii::$app->redis;
        
        $redis->hincrby(self::prefix.$roomNo, 'count', 1);
    }
    
    /**
     * 修改uid
     * @param unknown $roomNo
     * @param unknown $uid
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:52:07
     */
    public static function updateUid($roomNo, $uid) {
        $redis = \Yii::$app->redis;
        $redis->hset(self::prefix.$roomNo, 'uid', $uid);
    }
    
}

