<?php
namespace console\modules\paoma\models;

use paoma\models\PaomaAuth;
use yii\base\Model;

/**
 * 认证请求
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午2:53:24
 */
class AuthRequestForm extends Model{
    
    public $uuid;
    
    public function rules() {
        return [
            [['uuid'], 'required']
        ];
    }
    
    public function save(\swoole_server $server, \swoole_websocket_frame $frame, \swoole_table $authFdTable) {
        if (!$this->validate()) {
            return false;
        }
        //添加paoma_auth记录
        PaomaAuth::add($this->uuid);
        //保存authfd
        $authFdTable->set($this->uuid, ['fd'=>$frame->fd]);
    }
    
}

