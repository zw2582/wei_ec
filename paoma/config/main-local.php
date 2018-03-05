<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'pR6inqs8aLsWDQe5MEJxDIt6qdVslSUT',
        ],
        'redis'=>[
            'class'=>'yii\redis\Connection',
            'hostname'=>'120.79.30.72',
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
