<?php
namespace paoma\models;

use yii\base\Model;

/**
 * webstock连接对象
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月5日下午5:04:48
 */
class StockRequest extends Model{
    
    public $uuid;
    
    public $uid;
    
    public $action; //create:创建，join:加入，start：开始，play：摇动
    
    public function rules() {
        return [
            [['uuid', 'action'], 'required'],
            [['uid'], 'integer']
        ];
    }
    
    public function process() {
        //校验数据
        if (!$this->validate()) {
            return false;
        }
        if ($this->action == 'create') {
            //绑定uid和uuid
            PaomaUUid::setByUid($this->uid, $this->uuid);
            //设置房间状态为1.准备开赛，返回web
            $roomNo = PaomaUserRoom::get($this->uuid);
            Room::updateStatus($roomNo, 1);
            //返回用户uid，便于web端登录和获取用户信息
        }
    }
}

