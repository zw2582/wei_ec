<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUser;

/**
 * 退出房间
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午3:11:41
 */
class OutRoomForm extends Model{
    
    public $uid;
    
    public function rules() {
        return [
            ['uid', 'required']
        ];
    }
    
    public function save(\swoole_server $serv) {
        //1.接受参数uid
        if (!$this->validate()) {
            return false;
        }
        //2.paoma_room_user移除uid
        $user = PaomaUser::getUser($this->uid);
        if (!$user['room_no']) {
            $this->addError('caca', '用户'.$this->uid.'当前没有房间');
            return false;
        }
        PaomaRoomUsers::del($user['room_no'], $this->uid);
        //3.paoma_user的room_no变为0
        PaomaUser::saveUser($this->uid, ['room_no'=>0]);
        //4.通知房间内所有用户，用户退出
        Utils::sendTask($serv, $user['room_no'], ['action'=>'exit_room', 'userinfo'=>$user, 'uid'=>$this->uid]);
    }
}

