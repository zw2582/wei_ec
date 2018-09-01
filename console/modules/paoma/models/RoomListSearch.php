<?php
namespace console\modules\paoma\models;

use yii\base\Model;
use paoma\models\PaomaRoom;
use paoma\models\PaomaRoomUsers;
use paoma\models\PaomaUser;

/**
 * 房间搜索
 * @author zhangjiao
 *
 */
class RoomListSearch extends Model
{
    
    /**
     * 搜索房间列表
     * @param \swoole_server $serv
     * @param \swoole_table $phoneFdTable
     * @return array|array[]
     */
    public function search(\swoole_server $serv, \swoole_table $phoneFdTable) {
        $redis = \Yii::$app->redis;
        
        $paomaRooms = $redis->keys("paoma\_room\_[0-9]*");
        if (empty($paomaRooms)) {
            return [];
        }
        
        $rooms=[];
        foreach ($paomaRooms as $val) {
            $roomNo = trim($val, 'paoma_room_');
            $room = PaomaRoom::findOne($roomNo);
            $room['online'] = PaomaRoomUsers::count($roomNo);
            
            $fd = $phoneFdTable->get($room['uid'], 'fd');
            
            $room['zaixian'] = $serv->exist($fd) ? 1 : 0;   //1.代表房主上线，0.代表已下线
            $room['headimg'] = $redis->hget(PaomaUser::prefix.$room['uid'], 'headimg');
            
            $rooms[] = $room;
        }
        
        return $rooms;
    }
}

