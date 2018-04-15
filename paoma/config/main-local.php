<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '28gwOq9RpK66c6qW8BMEXIqGwTkFh4n0',
        ],
        'redis'=>[
            'class'=>'yii\redis\Connection',
            'hostname'=>'127.0.0.1',
            'port'=>6379,
            'database'=>0
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs'=>['*.*.*.*']
    ];
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
