<?php
namespace console\modules\paoma\models;

use yii\base\Model;

/**
 * 跑马认证表
 * 记录等待认证的数据，认证成功之后清除
 * 一个小时候自动清除，此时需要web端再次提交认证
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午5:51:44
 */
class PaomaAuth extends Model{
    
    const prefix = 'paoma_auth_';
    
    public static function add($uuid) {
        $redis = \Yii::$app->redis;
        
        \Yii::info('登记认证请求，uuid:'.$uuid, __METHOD__);
        return $redis->set(self::prefix.$uuid, 'wait auth', 'ex 3600');
    }
    
    public static function get($uuid) {
        $redis = \Yii::$app->redis;
        
        return $redis->get(self::prefix.$uuid);
    }
}

