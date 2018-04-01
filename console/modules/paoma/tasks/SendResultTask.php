<?php
namespace console\modules\paoma\tasks;

use paoma\models\PaomaRoom;
use paoma\models\PaomaRoomScore;
use console\modules\paoma\models\Utils;
/**
 * 发送任务结果给房间所有人
 * @author zhangjiao
 *
 */
class SendResultTask {
    
    private static $mstime = 1000;
    
    public static function execute(\swoole_server $serv, $data) {
        $roomNo = $data['room_no'];
        
        $serv->tick(self::mstime, function($tickId) use ($serv, $roomNo){
            //查询几乎所有的分值
            $result = PaomaRoomScore::listScores($roomNo,0,1000);
            //计算最低分,最高分,总体排名
            $values = array_values($result);
            $max = empty($values) ? 1 : max($values);
            $min = empty($values) ? 0 : min($values);
            $ranks = empty($result)?[]:array_flip(array_keys($result));
            
            //发送给房间所有用户
            Utils::sendTask($serv, $roomNo, [
                'action'=>'result',
                'max'=>$max,
                'min'=>$min,
                'result'=>$result,
                'ranks'=>$ranks
            ]);
            if (!PaomaRoomScore::status($roomNo)) {
                //修改房间状态为已结束
                PaomaRoom::updateStatus($roomNo, 3);
                //@todo分配奖金,也可能留给展示排名的时候
                //告知所有人员比赛结束,停止推送结果
                Utils::sendTask($serv, $roomNo, ['action'=>'stop']);
                //结束定时器
                $serv->clearTimer($tickId);
            }
        });
    }
}

