<?php
defined('DEFAULT_SUPPID') || define('DEFAULT_SUPPID', 1);    //定义默认供应商id
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

if (YII_DEBUG) {
    //开发环境
    defined('PIC_URL') || define('PIC_URL', 'http://dev.picture.integle.com');
    defined('PIC_DIR') || define('PIC_DIR', 'D:/tools/pic');    //订单图片目录
} else {
    //线上环境
    defined('PIC_URL') || define('PIC_URL', 'http://wepic.iillyy.com');
    defined('PIC_DIR') || define('PIC_DIR', '/data/uploads');    //订单图片目录
}
