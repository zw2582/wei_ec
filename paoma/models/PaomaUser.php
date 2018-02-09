<?php
namespace paoma\models;

use yii\base\Model;

class PaomaUser extends Model{
    
    public $uuid;
    
    public $uid;
    
    public $uname;
    
    public $headimg;
    
    //用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
    public $sex;
    
    private static $self;
    
    /**
     * 查看当前跑马用户信息
     * @return \paoma\models\PaomaUser
     * wei.w.zhou@integle.com
     * 2018年2月5日上午10:49:25
     */
    public static function current() {
        if (self::$self == null) {
            self::$self = new self();
            if (\Yii::$app->user->isGuest) {
                //生成uuid，等待登录后绑定uid
                self::$self->uuid = uniqid();
                self::$self->uid = 0;
            } else {
                $uId = \Yii::$app->user->id;
                
                $uuid = PaomaUUid::getByUid($uId);
                //用户已登录，创建uuid，并绑定
                if (empty($uuid)) {
                    $uuid = uniqid();
                    PaomaUUid::setByUid($uId, $uuid);
                }
                
                $user = \Yii::$app->user->identity;
                self::$self->uuid = $uuid;
                self::$self->uid = $uId;
                self::$self->uname = $user->username;
                self::$self->headimg = headimgurl;
                self::$self->sex = sex;
            }
        }
        return self::$self;
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

