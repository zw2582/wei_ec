<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class AmazeUiAssets extends AssetBundle{
    
    public $css = [
        'http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.css',
        'http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css',
    ];
    
    public $js = [
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.js',
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js',
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.ie8polyfill.js',
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.ie8polyfill.min.js',
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.widgets.helper.js',
        'http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.widgets.helper.min.js'
    ];
}

