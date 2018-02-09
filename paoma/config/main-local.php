<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'pR6inqs8aLsWDQe5MEJxDIt6qdVslSUT',
        ],
        'redis'=>[
            'class'=>'paoma\console\models\RedisPool',
            'hostname'=>'192.168.100.18',
            'port'=>6379,
            'database'=>6
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
