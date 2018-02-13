<?php
namespace paoma\models;

use yii\base\Model;
use common\models\User;

class PaomaUser extends Model{
    
    public $uuid;
    
    public $uid;
    
    public $uname;
    
    public $headimg;
    
    //用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
    public $sex;
    
    private static $self;
    
    const sess_uuid_key = '__uuid';
    
    const prefix = 'paoma_user_';
    
    /**
     * 查看当前跑马用户信息
     * 首先已传入的uuid为主，其次已登录的uuid为主，最后以session的uuid为主，然后才是uuid生成
     * @return \paoma\models\PaomaUser
     * wei.w.zhou@integle.com
     * 2018年2月5日上午10:49:25
     */
    public static function current($existUUid=null) {
        if (empty($existUUid)) {
            $existUUid = \Yii::$app->session->get(self::sess_uuid_key);
        }
        if (self::$self == null) {
            self::$self = new self();
            if (\Yii::$app->user->isGuest) {
                if (empty($existUUid)) {
                    //生成uuid，等待登录后绑定uid
                    self::$self->uuid = uniqid();
                } else {
                    self::$self->uuid = $existUUid;
                }
                //新生成的uuid保存到session中
                \Yii::$app->session->set(self::sess_uuid_key, self::$self->uuid);
                self::$self->uid = 0;
            } else {
                $uId = \Yii::$app->user->id;
                
                $uuid = PaomaUUid::getByUid($uId);
                //用户已登录，创建uuid，并绑定
                if (empty($uuid)) {
                    if (empty($existUUid)) {
                        $uuid = uniqid();
                    } else {
                        $uuid = $existUUid;
                    }
                    PaomaUUid::setByUid($uId, $uuid);
                } 
                
                $user = \Yii::$app->user->identity;
                self::$self->uuid = $uuid;
                self::$self->uid = $uId;
                self::$self->uname = $user->username;
                self::$self->headimg = $user->headimgurl;
                self::$self->sex = $user->sex;
            }
        }
        return self::$self;
    }
    
    private $_uuid = FALSE;
    public function getUuid() {
        if ($this->uuid === false) {
            
        }
    }
    
    public function setUuid($uuid) {
        
    }
    
    public function saveUser() {
        $redis = \Yii::$app->redis;
        $uuid = $this->uuid;
        $redis->hset(self::prefix.$uuid, 'uid', $this->uid);
        $redis->hset(self::prefix.$uuid, 'uname', $this->uname);
        $redis->hset(self::prefix.$uuid, 'headimg', $this->headimg);
        $redis->hset(self::prefix.$uuid, 'sex', $this->sex);
    }
    
    public static function getUser($uuid) {
        $redis = \Yii::$app->redis;
        return [
            'uid'=>$redis->hget(self::prefix.$uuid, 'uid'),
            'uname'=>$redis->hget(self::prefix.$uuid, 'uname'),
            'headimg'=>$redis->hget(self::prefix.$uuid, 'headimg'),
            'sex'=>$redis->hget(self::prefix.$uuid, 'sex'),
        ];
    }
    
    /**
     * 返回当前房间
     * @return string 房间号
     * wei.w.zhou@integle.com
     * 2018年2月5日下午2:34:17
     */
    public function currentRoomNo() {
        return PaomaUserRoom::get($this->uuid);
    }
    
}

