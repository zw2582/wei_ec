<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaRoomScore;
use paoma\models\PaomaUser;
use paoma\models\PaomaRoom;

/**
 * 摇动
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午3:39:40
 */
class PlayForm extends Model{
    
    public $uid;
    
    public $count;
    
    public function rules() {
        return [
            [['uid','count'], 'required']
        ];
    }
    
    public function save(\swoole_server $server, $fd) {
        //获取用户
        $user = PaomaUser::getUser($this->uid);
        if (empty($user)) {
            $this->addError('play', '用户不存在');
            return false;
        }
        if (empty($user['room_no'])) {
            $this->addError('play', '用户还没有加入任何房间');
            return false;
        }
        //获取房间
        $room = PaomaRoom::findOne($user['room_no']);
        if (!$room['isactive'] || $room['isactive'] == 1) {
            $this->addError('play', '比赛未开始');
            return false;
        }
        if ($room['isactive'] == 3) {
            Utils::sendSucc($server, $fd, [
                'action'=>'play',
                'over'=>1
            ]);
            return true;
        }
        //增加步数
        if (!PaomaRoomScore::add($user['room_no'], $this->uid, $this->count)) {
            Utils::sendSucc($server, $fd, [
                'action'=>'play',
                'over'=>1
            ]);
            return true;
        }
        return true;
    }
}

