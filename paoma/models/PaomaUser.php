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
    
    /*
     * 存储用户当前所在房间编号
     */
    const prefix_user_roomno = 'paoma_user_roomno_';

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
     * 设置当前房间号,最好由Room类调用
     * 
     * @see Room::join()
     * @param string $roomNo
     * wei.w.zhou@integle.com
     * 2018年2月5日下午1:45:33
     */
    public function setCurrentRoomNo($roomNo) {
        if (empty($this->uuid)) {
            \Yii::info('没有uuid无法进入房间');
            return;
        }
        $redis = \Yii::$app->redis;
        //设置用户所在的房间号
        $redis->set(self::prefix_user_roomno.$this->uuid, $roomNo);
        //增加房间内的用户
        
    }
    
    /**
     * 返回当前房间
     * @return string 房间号
     * wei.w.zhou@integle.com
     * 2018年2月5日下午2:34:17
     */
    public function currentRoomNo() {
        $redis = \Yii::$app->redis;
        return $redis->get(self::prefix_user_roomno.$this->uuid);
    }
    
    /**
     * 删除当前房间编号，最好只有Room调用
     * @see Room::exitRoom()
     * 
     * wei.w.zhou@integle.com
     * 2018年2月5日下午2:13:26
     */
    public function delCurrentRoomNo() {
        $redis = \Yii::$app->redis;
        
        $redis->del(self::prefix_user_roomno.$this->uuid);
    }
}

