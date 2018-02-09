<?php
namespace paoma\console\models;

use yii\base\Model;

class PaomaReport extends Model{
    
    const prefix = 'paoma_report_';
    
    /**
     * 设置当前汇报结果的任务id
     * @param unknown $roomNo
     * @param unknown $taskId
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:33:05
     */
    public static function set($roomNo, $taskId) {
        $redis = \Yii::$app->redis;
        
        $redis->set(self::prefix.$roomNo, $taskId, 'nx');
        $tId = $redis->get(self::prefix.$roomNo);
        if ($taskId == $tId) {
            return true;
        }
        return false;
    }
    
    /**
     * 获取当前房间的汇报task
     * @param unknown $roomNo
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月8日上午11:34:29
     */
    public static function get($roomNo) {
        $redis = \Yii::$app->redis;
        return $redis->get(self::prefix.$roomNo);
    }
}

