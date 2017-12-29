<?php
namespace backend\assets;

use yii\web\AssetBundle;

class ElementAsset extends AssetBundle {
    
    public $css = [
        'https://unpkg.com/element-ui/lib/theme-chalk/index.css'
    ];
    
    public $js = [
        'https://unpkg.com/element-ui/lib/index.js'
    ];
}

