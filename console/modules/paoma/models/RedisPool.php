<?php
namespace console\modules\paoma\models;

use SplQueue;
use yii\redis\Connection;
use Swoole\Lock;

/**
 * redis连接池
 * @author wei.w.zhou@integle.com
 *
 * 2018年2月7日下午4:28:57
 */
class RedisPool extends Connection{
    
    public $min = 3;
    
    public $init = 5;
    
    public $max = 100;
    
    private $pool;
    
    private $lock;
    
    public function init() {
        parent::init();
        
        $this->pool = new SplQueue();
        
        if (extension_loaded("swoole")) {
            $this->lock = new Lock(SWOOLE_MUTEX);
        }
        
        //每分钟检测redis链接是否过大
        if (extension_loaded("swoole")) {
            swoole_timer_tick(60*1000, function() {
                $count = $this->pool->count();
                \Yii::info(sprintf("timer_tick:redis pool size:%d", $count), 'redis_pool');
                if ($count >= $this->max) {
                    do {
                        $conn = $this->pool->pop();
                        $conn->close();
                    } while($this->pool->count() <= $this->init);
                }
            });
        }
    }
    
    /**
     * 先试试看，不行就得加锁
     * @return mixed
     * wei.w.zhou@integle.com
     * 2018年2月7日下午5:36:56
     */
    public function getRedis() {
        if ($this->lock) {
            $this->lock->lock();
        }
        //如果池子里面的数量小于最低数量则创建连接
        if ($this->pool->count() < $this->min) {
            $conn = new Connection();
            $conn->hostname = $this->hostname;
            $conn->password = $this->password;
            $conn->database = $this->database;
            $conn->port = $this->port;
            //加入连接池
            $this->pool->push($conn);
            \Yii::info("创建新的redis连接加入连接池，当前连接数：".$this->pool->count(), "redis_pool");
        }
        if ($this->lock) {
            $this->lock->unlock();
        }
        return $this->pool->pop();
    }
    
    /**
     * 执行命令
     * {@inheritDoc}
     * @see \yii\redis\Connection::executeCommand()
     */
    public function executeCommand($name, $params = []) {
        \Yii::info("使用线程池执行命令:".$name, "redis_pool");
        $conn = $this->getRedis();
        $data = $conn->executeCommand($name, $params);
        
        $this->pool->push($conn);
        return $data;
    }
    
}

