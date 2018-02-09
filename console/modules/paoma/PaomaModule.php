<?php
namespace console\modules\paoma;

use yii\base\Module;

class PaomaModule extends Module{
    
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'console\modules\paoma\controllers';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // custom initialization code goes here
    }
}

