<?php
namespace frontend\controllers;

use Traversable;

class Person implements \Iterator{
    
    private $arr = [1,2,3,4];
    private $index = 0;
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
    
    public function getException() {
        throw new \Exception();
    }

}

