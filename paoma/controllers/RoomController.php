<?php
namespace paoma\controllers;

use common\controllers\BasicController;
use yii\base\UserException;
use paoma\models\PaomaUser;
use paoma\models\PaomaRoom;
use paoma\models\PaomaRoomUsers;
use common\models\User;
use paoma\models\PaomaRoomScore;
use yii\db\Expression;

/**
 * 房间管理
 * @author zhangjiao
 *
 */
class RoomController extends BasicController{
    
    /**
     * 查看房间信息接口
     * 
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:40:01
     */
    public function actionInfo() {
        $roomNo = \Yii::$app->request->get('room_no');
        
        $room = PaomaRoom::findOne($roomNo);
        return $this->ajaxSuccess($room);
    }
    
    /**
     * 查看房间成员接口
     * 
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:40:20
     */
    public function actionUsers() {
        $roomNo = \Yii::$app->request->get('room_no');
        
        $userIds = PaomaRoomUsers::members($roomNo);
        
        $users = User::find()->select('id as uid,username as uname,sex,headimgurl headimg')
            //->addSelect(new Expression('0 as score,0.5 as rate'))
            ->where(['id'=>$userIds])->asArray()->all();
        
        return $this->ajaxSuccess($users);
    }
    
    /**
     * 查看房间成员分值接口
     * 返回2代表已结束，1.代表进行中
     * 
     * wei.w.zhou@integle.com
     * 2018年3月7日下午4:43:23
     */
    public function actionScores() {
        $roomNo = \Yii::$app->request->get('room_no');
        $uid = \Yii::$app->request->get('uid');
        
        if (!PaomaRoomScore::status($roomNo)) {
            //修改房间状态为已结束
            PaomaRoom::updateStatus($roomNo, 3);
            //@todo分配奖金
            //比赛已结束返回比赛结果
            $result = PaomaRoomScore::listScores($roomNo);
            $rank = PaomaRoomScore::rank($roomNo, $uid);
            return $this->ajaxReturn(2, [
                'max'=>empty($result)?1:max(array_values($result)),
                'rank'=>$rank,
                'result'=>$result,
                'ranks'=>empty($result)?[]:array_flip(array_keys($result))
            ], '比赛已结束');
        }
        //查询几乎所有的分值
        $data = PaomaRoomScore::listScores($roomNo,0,1000);
        
        $rank = PaomaRoomScore::rank($roomNo, $uid);
        
        return $this->ajaxSuccess([
            'max'=>empty($data)?1:max(array_values($data)),
            'rank'=>$rank,
            'result'=>$data,
            'ranks'=>empty($data)?[]:array_flip(array_keys($data))
        ]);
    }
    
    /**
     * 创建房间
     * 先创建房间，在判断房主是否登录
     */
    public function actionCreate() {
        if (\Yii::$app->user->isGuest) {
            return $this->ajaxFail('请先登录');
        }
        $user = PaomaUser::getUser(\Yii::$app->user->id, FALSE);
        \Yii::info(json_encode($user), __METHOD__);
        if ($user['room_no']) {
            return $this->ajaxFail('请先退出当前所在房间');
        }
        //创建房间
        $roomNo = PaomaRoom::create($user);
        
        //保存当前创建的房间
        PaomaUser::saveUser(\Yii::$app->user->id, ['room_no'=>$roomNo]);

        return $this->ajaxSuccess($roomNo, '创建成功');
    }
}

