<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUser;
use paoma\models\PaomaRoom;

/**
 * 加入房间
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午1:45:07
 */
class EnterRoomForm extends Model{
    
    public $uid;
    
    public $room_no;
    
    public function rules() {
        return [
            [['uid', 'room_no'], 'required']
        ];
    }
    
    public function save(\swoole_server $serv, \swoole_table $webFdTb, \swoole_table $phoneFdTb) {
        //校验参数
        if (!$this->validate()) {
            return false;
        }
        //判断房间是否存在
        $room = PaomaRoom::findOne($this->room_no);
        if (empty($room)) {
            $this->addError('room', '房间不存在');
            return false;
        }
        //paoma_room_user加入uid记录，不可重复
        PaomaRoomUsers::add($this->room_no, $this->uid);
        //设置用户当前房间
        PaomaUser::saveUser($this->uid, ['room_no'=>$this->room_no]);
        //通知房间内所有用户，新用户加入信息
        $userinfo = PaomaUser::getUser($this->uid);
        //发送给任务异步通知
        Utils::sendTask($serv, $this->room_no, [
            'action'=>'join', 
            'user'=>$userinfo, 
            'uid'=>$this->uid,
            'room_no'=>$this->room_no
        ]);
        return true;
    }
}

