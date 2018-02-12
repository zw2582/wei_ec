<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-paoma',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'paoma\controllers',
    'defaultRoute' => 'site/index',
    'modules' => require(__DIR__ . '/module.php'),
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-paoma',
        ],
        'weiauthor' => [
            'class'=>'frontend\models\WeiAuthor',
            'appid'=>'wx42f761126784f81c',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-paoma', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'paoma',
            'class' => 'yii\redis\Session',
            'redis' => 'redis',
            'keyPrefix'=>'PHPREDIS_SESSION:',
            'timeOut' => 3600,
            'cookieParams' => [
                'domain' => 'paoma.com',
                'lifetime' => 3600,
                'httpOnly' => TRUE,
                'path' => '/'
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
