<?php
namespace console\modules\paoma\models;

use paoma\models\PaomaAuth;
use yii\base\Model;

/**
 * 认证确认
 * action:auth_confirm
 * @author wei.w.zhou@integle.com
 *
 * 2018年3月7日下午3:01:47
 */
class AuthConfirmForm extends Model{
    
    public $uuid;
    
    public $uid;
    
    public function rules() {
        return [
            [['uuid', 'uid'], 'required']
        ];
    }
    
    public function save(\swoole_server $serv, \swoole_websocket_frame $frame, 
        \swoole_table $webFdTb, \swoole_table $authFdTb) {
        //1.校验参数
        if (!$this->validate()) {
            return false;
        }
        //获取待审核的认证
        $wait = PaomaAuth::get($this->uuid);
        if (empty($wait)) {
            \Yii::error(sprintf("uuid:%s 认证已过期，请刷新二维码重新发起认证", $this->uuid), "auth_confirm");
            return Utils::sendFail($serv, $frame->fd, '认证已过期，请刷新二维码重新发起认证');
        }
        //判断返回的fd是否还存在且有效
        $authfd = $authFdTb->get($this->uuid, 'fd');
        if (!($authfd && $serv->exist($authfd))) {
            \Yii::error(sprintf("uuid:%s 服务端的fd链接已断开，无法返回认证信息", $this->uuid), 'auth_confirm');
            return Utils::sendFail($serv, $frame->fd, '您的网页端连接已断开');
        }
        //设置paoma_auth的uuid的值为uid
        PaomaAuth::set($this->uuid, $this->uid);
        
        //移除authFdTable的fd到webFdTable中
        $webFdTb->set($this->uid, $authfd);
        $authFdTb->del($this->uuid);
        
        //发送uuid，uid到authFdTable中
        return Utils::sendSucc($serv, $authfd, [
            'action'=>'auth_confirm',
            'uuid'=>$this->uuid,
            'uid'=>$this->uid
        ]);
    }
}

