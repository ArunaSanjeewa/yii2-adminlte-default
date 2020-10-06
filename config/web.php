<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'APT Cool Mate',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'MaiAW4ds684njPcZQWBwsQdlzK',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
           
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 'user' => [
        //     'identityClass' => 'app\models\User',
        //     'enableAutoLogin' => true,
        // ],
       
        'user' => [
            'identityClass' => 'webvimark\modules\UserManagement\models\User',           
            'enableAutoLogin' => true,
            'authTimeout'=>60*30,
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            //'loginUrl' => './../auth/login',
            'on beforeRequest' => function ($event) {
               
                
            },
            // Comment this if you don't want to record user logins
            'on afterLogin' => function($event) {
                
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'AIzaSyAYozV6DqCBUyNwAWldNbfegfObbyZR0OM',
                        'language' => 'en',
                        'version' => '3.1.18'
                    ]
                ]
            ]
        ],
       
        'db' => $db,
        'urlManager' => [
            
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                '1/register'=>'site/register',
                '1/authorize'=>'site/authorize',
                '1/accesstoken'=>'site/accesstoken',
                '1/me'=>'site/me',
                '1/logout'=>'site/logout',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',      
                
              
                
             ],


        ],
        
    ],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'gridviewKrajee' => [
            'class' => '\kartik\grid\Module',
        // your other grid module settings
        ],
        'api' => [

            'class' => 'app\modules\ApiModule',
            'class' => 'app\modules\ApiModule',


        ],
        
       
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            // 'enableRegistration' => true,
            // Add regexp validation to passwords. Default pattern does not restrict user and can enter any set of characters.
            // The example below allows user to enter :
            // any set of characters
            // (?=\S{8,}): of at least length 8
            // (?=\S*[a-z]): containing at least one lowercase letter
            // (?=\S*[A-Z]): and at least one uppercase letter
            // (?=\S*[\d]): and at least one number
            // $: anchored to the end of the string
            //'passwordRegexp' => '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$',
            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction' => function(yii\base\ActionEvent $event) {
                if ($event->action->uniqueId == 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },
        ],
        
        // 'audit' => [ 
        //     'class'=>'bedezign\yii2\audit\Audit',
        // ]
        
    ],
 
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
