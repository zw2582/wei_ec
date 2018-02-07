<?php
namespace paoma\console\models;

use yii\base\Model;
use paoma\console\PaomaHandler;

/**
 * 所有请求数据
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午3:10:27
 */
class RequestData extends Model{
    
    /**
     * 请求类型
     * auth_request 认证请求
     * auth_confirm 确认认证
     * play 摇晃手机
     * start 开始比赛
     * exit 退出房间
     * enter 进入房间
     */
    public $action;
    
    //跑马用户唯一认证
    public $uuid;
    
    //微信openid
    public $openid;
    
    //房间号
    public $room_no;
    
    //摇晃的次数（每次请求）
    public $count;
    
    /**
     * @var PaomaHandler websocket请求处理器，保存了fd资源表
     */
    private $handler;
    
    public function rules() {
        return [
            [['action','uuid'], 'required'],
            [['action', 'uuid','openid'], 'string'],
            [['count'], 'integer'],
            ['room_no', 'safe']
        ];
    }
    
    /**
     * 执行业务逻辑
     * @param PaomaHandler $handler
     * wei.w.zhou@integle.com
     * 2018年2月7日下午3:46:37
     */
    public function process(PaomaHandler $handler) {
        $this->handler = $handler;
        switch ($this->action) {
            case 'auth_request':
                return $this->actionAuthRequest();
            case 'auth_confirm':
                return $this->actionAuthConfirm();
            case 'play':
                return $this->play();
            case 'start':
                return $this->start();  //开始启动
            case 'exit':
                return $this->exitRoom();
            case 'enter':
                return $this->enterRoom();
            default:
                return;
        }
    }
    
    /**
     * 认证请求
     * 存储用户的认证请求，有效期一个小时，等待客户端确认请求
     * wei.w.zhou@integle.com
     * 2018年2月7日下午3:52:45
     */
    public function actionAuthRequest() {
        return PaomaAuth::add($this->uuid);
    }
    
    public function actionAuthConfirm(){
        
    }
    
}

