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
 * isactive:是否激活，1.准备开赛，等待马入场，0.未开赛，可能房主还未登录，此时不能加入,2.正在比赛，3.比赛结束
 * online:当前人数
 * count:游戏回合
 *
 * 2018年2月5日上午9:48:01
 */
class Room extends Model{
    
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
        return $redis->get(self::prefix.$roomNo);
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
        \Yii::info(sprintf("uuid:%s 创建房间:%s", $user->uuid, $roomNo), __METHOD__);
        
        //房间基本信息
        $redis->hset(self::prefix.$roomNo, 'room_no', $roomNo);
        $redis->hset(self::prefix.$roomNo, 'uuid', $user->uuid);
        $redis->hset(self::prefix.$roomNo, 'uid', $user->uid);
        $redis->hset(self::prefix.$roomNo, 'uname', $user->uname);
        $redis->hset(self::prefix.$roomNo, 'isactive', $user->uid?1:0);
        
        //加入当前房间
        self::join($roomNo, $user);
        
        return $roomNo;
    }
    
    /**
     * 进入房间
     * @param PaomaUser $user
     * wei.w.zhou@integle.com
     * 2018年2月5日下午2:41:49
     */
    public static function join($roomNo, PaomaUser $user) {
        \Yii::info(sprintf("uuid:%s 进入房间:%s", $user->uuid, $roomNo), __METHOD__);
        $redis = \Yii::$app->redis;
        
        //如果用户原来有房间先退出
        if ($oldRoomNo = $user->currentRoomNo()) {
            if ($oldRoomNo == $roomNo) {
                return;
            } else {
                PaomaRoomUsers::exitRoom($oldRoomNo, $user->uuid);
            }
        }
        //用户进入房间
        PaomaRoomUsers::add($roomNo, $user->uuid);
        //设置用户当前房间
        PaomaUserRoom::set($user->uuid, $roomNo);
    }
    
    /**
     * 修改房间状态
     * @param integer $status 1.准备开赛，等待马入场，0.未开赛，可能房主还未登录，此时不能加入,2.正在比赛，3.比赛结束
     * wei.w.zhou@integle.com
     * 2018年2月5日下午5:31:34
     */
    public static function updateStatus($roomNo, $status) {
        if (!is_array($status, [0,1,2,3])) {
            throw new UserException('参数不合法');
        }
        $redis = \Yii::$app->redis;
        $redis->hset(self::prefix.$roomNo, 'isactive', $status);
    }
    
    /**
     * 退出当前房间
     * @param string $roomNo
     * @param PaomaUser $user
     * wei.w.zhou@integle.com
     * 2018年2月5日下午2:51:51
     */
    public static function exitRoom($roomNo, PaomaUser $user) {
        \Yii::info(sprintf("uuid:%s 退出房间:%s", $user->uuid, $roomNo), __METHOD__);
        $redis = \Yii::$app->redis;
        
        //用户退出当前房间
        PaomaRoomUsers::exitRoom($roomNo, $user->uuid);
        //设置用户当前房间为空
        PaomaUserRoom::del($user->uuid);
    }
}

