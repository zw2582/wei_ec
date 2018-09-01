<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'redis'=>[
            'class'=>'yii\redis\Connection',
            'hostname'=>'127.0.0.1',
            'port'=>6379,
            'database'=>0
        ]
    ],
];
