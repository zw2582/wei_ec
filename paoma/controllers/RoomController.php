<?php
namespace paoma\controllers;

use common\controllers\BasicController;
use yii\base\UserException;
use paoma\models\Room;
use paoma\models\PaomaUUid;
use paoma\models\PaomaUser;

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
     * 先创建房间，在判断房主是否登录
     */
    public function actionCreate() {
        //查看当前跑马用户信息
        $paomaUser = PaomaUser::current();
        
        //创建房间
        $roomNo = Room::create($paomaUser);
        
        return $this->render('room', [
            'master'=>$paomaUser,
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
        
        $room = Room::findOne($roomNo);
        if (!$room) {
            throw new UserException('该房间不存在，请选择其他房间或者创建房间');
        }
        //查看当前跑马用户信息
        $paomaUser = PaomaUser::current();
        
        return $this->render('room', [
            'room_no'=>$roomNo,
            'uuid'=>$paomaUser->uuid,
            'uid'=>$paomaUser->uid,
            'action'=>'join'
        ]);
    }
    
    /**
     * 退出房间
     */
    public function actionOut() {
        
    }
    
}

