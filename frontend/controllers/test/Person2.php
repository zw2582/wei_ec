<?php
namespace frontend\controllers\test;

use IteratorAggregate;

class Person2 implements IteratorAggregate {
    
    public function getIterator() {
        return new \ArrayIterator([1,2,3,4,5,6]);
    }

}

