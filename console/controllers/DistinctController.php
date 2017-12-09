<?php
namespace console\controllers;

use yii\console\Controller;

/**
 * 初始化省市区
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月9日下午3:55:21
 */
class DistinctController extends Controller{
    
    public function actionRun($path) {
        if (!file_exists($path) || !is_file($path)) {
            return $this->stderr('file not exist');
        }
        $handle = fopen($path, 'rb');
        
        $a = $b = $c = 0;
        while (!feof($handle)) {
//             $c = fgetc($handle);
//             var_dump(ord($c));die;
            
            $line = fgets($handle);
//             \Yii::info('|'.trim($line, chr(239)).'|');
//             continue;
            $line = mb_convert_encoding($line, 'utf-8');
            $line = trim($line);
            $lines = preg_split('/,/', $line);
            
            $number = trim($lines[0]);
            $name = trim($lines[1]);
            \Yii::info('|'.$number.'|,|'.$name.'|');
            
            $sql = 'insert into sys_distinct(number,name,parent) value("%s","%s","%s");';
            if (preg_match('/[0-9]{2}0000/', $number)) {
                $a++;
                \Yii::info(sprintf($sql, $number, $name, '000000'), 'caca');
            } elseif (preg_match('/\b\d+[1-9]0{2,3}\b/', $number)) {
                $b++;
                $parent = mb_substr($number, 0, 2);
                \Yii::info(sprintf($sql, $number, $name, $parent.'0000'), 'caca');
            } else {
                $c++;
                $parent = mb_substr($number, 0, 4);
                \Yii::info(sprintf($sql, $number, $name, $parent.'00'), 'caca');
            }
        }
        
        $this->stdout(sprintf('complete,a:%d,b:%d,c:%d', $a, $b, $c));
    }
}

