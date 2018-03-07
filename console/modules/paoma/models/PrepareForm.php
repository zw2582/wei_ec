<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaRoom;
use paoma\models\PaomaRoomScore;

/**
 * 准备
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午3:25:03
 */
class PrepareForm extends Model{
    
    public $uid;
    
    public $room_no;
    
    public function rules() {
        return [
            [['uid','room_no'], 'required']
        ];
    }
    
    public function save(\swoole_server $server) {
        //1.接受参数uid
        if (!$this->validate()) {
            return false;
        }
        //判断是否是房主操作
        $room = PaomaRoom::findOne($this->room_no);
        if ($room['uid'] != $this->uid) {
            $this->addError('caca', '只有房主才能操作准备');
            return false;
        }
        //清除上一次比赛结果
        PaomaRoomScore::clear($this->room_no);
        //修改房间status为1
        PaomaRoom::updateStatus($this->room_no, 1);
        //通知房间内所有用户当前房间状态
        Utils::sendTask($server, $this->room_no, ['action'=>'prepare']);
    }
}

