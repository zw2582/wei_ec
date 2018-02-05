<?php
namespace paoma\controllers;

use common\controllers\BasicController;
use yii\base\UserException;

/**
 * 房间管理
 * @author zhangjiao
 *
 */
class RoomController extends BasicController{
    
    /**
     * 房间主页
     */
    public function actionIndex() {
        
    }
    
    /**
     * 创建房间
     * 必须在登录的情况下才能创建房间
     */
    public function actionCreate() {
        //创建房间号
        $roomNo = time();
        //校验房间是否存在
        $redis = \Yii::$app->redis;
        do {
            $isExist = $reids->get('paoma_room_'.$roomNo);
            $roomNo++;
        } while ($isExist);
        
        /*
         * 判断用户是否登录，没有登录或用户的uuid为空则创建连接uuid
         * uuid用户创建webstock连接，或者复活webstock连接
         */
        if (\Yii::$app->user->isGuest) {
            $uuid = uuid_create();
            $uId = 0;
        } else {
            $uId = \Yii::$app->user->id;
            
            $uuid = $redis->get('paoma_uuid_'.$uId);
            if (empty($uuid)) {
                $uuid = uuid_create();
                $redis->set('paoma_uuid_'.$uId, $uuid);
            }
        }
        
        //创建房间
        $reids->hset('paoma_room_'.$roomNo, [
            'uuid'=>$uuid,
            'uid'=>$uId,
            'isactive'=>0
        ]);
        
        return $this->render('room', [
            'uuid'=>$uuid,
            'uId'=>$uId,
            'room_no'=>$roomNo,
            'action'=>'create'
        ]);
    }
    
    /**
     * 进入房间
     */
    public function actionJoin() {
        $roomNo = \Yii::$app->request->get('roomno');
        
        //判断房间是否存在
        $redis = \Yii::$app->redis;
        
        /*
         * paoma_room_no结构存储房间的基本信息，hash类型
         * uuid：房主uuid,
         * uid:房主用户id
         * uname:房主名称
         * isactive:是否激活，1.激活，0.等待激活，可能房主还未登录，此时不能加入
         * online:当前人数
         * count:游戏回合
         */
        $room = $redis->get('paoma_room_'.$roomNo);
        if (!$room) {
            throw new UserException('该房间不存在，请选择其他房间或者创建房间');
        }
        /*
         * 判断用户是否登录，没有登录或用户的uuid为空则创建连接uuid
         * uuid用户创建webstock连接，或者复活webstock连接
         */
        if (\Yii::$app->user->isGuest) {
            $uuid = uuid_create();
            $uId = 0;
        } else {
            $uId = \Yii::$app->user->id;
            /*
             * paoma_uuid_uid：用户关联uuid和用户id，字符串类型
             */
            $uuid = $redis->get('paoma_uuid_'.$uId);
            if (empty($uuid)) {
                $uuid = uuid_create();
                $redis->set('paoma_uuid_'.$uId, $uuid);
            }
        }
        
        return $this->render('room', [
            'room_no'=>$roomNo,
            'uuid'=>$uuid,
            'uId'=>$uId,
            'action'=>'join'
        ]);
    }
    
    /**
     * 退出房间
     */
    public function actionOut() {
        
    }
    
}

