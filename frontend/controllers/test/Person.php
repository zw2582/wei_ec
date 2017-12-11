<?php
namespace frontend\controllers\test;


class Person implements \Iterator,\ArrayAccess,\Serializable{
    
    private $arr = [1,2,3,4];
    private $index = 0;
    
    private $name;
    
    private $sex;
    
    private $data;
    
    public $gogo;
    
    public function next() {
        $this->index++;
    }

    public function valid() {
        return isset($this->arr[$this->index]);
    }

    public function current() {
        return $this->arr[$this->index];
    }

    public function rewind() {
        $this->index = 0;
    }

    public function key() {
        return $this->index;
    }
    
    /**
     * 可数组形式访问
     */
    public function getException() {
        throw new \Exception();
    }
    public function offsetGet($offset) {
        echo 'get propery</br>';
        return $this->{$offset};
    }

    public function offsetExists($offset) {
        echo 'detected has propery</br>';
        return isset($this->{$offset});
    }

    public function offsetUnset($offset) {
        echo 'unset propery</br>';
        $this->{$offset} = null;
    }

    public function offsetSet($offset, $value) {
        echo 'set propery</br>';
        $this->{$offset} = $value;
    }

    public function __isset($offset) {
        echo 'invoke __isset</br>';
    }
    
    /**
     * serializable
     */
    public function __construct($name = null, $sex = null, $data = null) {
        \Yii::info('执行person的construct函数', __METHOD__);
        $this->name = $name;
        $this->sex = $sex;
        $this->data = $data;
    }
    
    public function __destruct() {
        \Yii::info('执行person的destruct函数', __METHOD__);
    }
    
    public function __sleep() {
        \Yii::info('执行person的sleep函数', __METHOD__);
        return ['name', 'sex', 'data'];
    }
    
    public function __wakeup() {
        \Yii::info('执行person的weakup函数', __METHOD__);
    }
    public function serialize() {
        \Yii::info('执行Serializable::serialize函数', __METHOD__);
         return json_encode(['name'=>$this->name, 'sex'=>$this->sex, 'data'=>$this->data]);
    }

    public function unserialize($serialized) {
        \Yii::info('执行Serializable::unserialize函数', __METHOD__);
        $data = json_decode($serialized, true);
        extract($data);
        $this->name = $name;
        $this->sex = $sex;
        $this->data = $data;
        
    }


}

