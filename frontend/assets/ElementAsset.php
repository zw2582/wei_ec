<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Vue.js
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月19日下午9:11:16
 */
class ElementAsset extends AssetBundle {
    
    public $baseUrl = '@web';
    public $css = [
        'css/bin/element/element-ui2.0.10.css',
        //'css/style.css'
    ];
    public $js = [
        'js/vue.min.js',
        'js/bin/element/element-ui2.0.10.js',
        'js/element/index.js'
    ];
}

