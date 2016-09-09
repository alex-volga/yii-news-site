<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'EventsManager', 'SiteNotification'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'GI_9UOvknfTcg-TyPwtwYHO1Heyo24P9',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            'enableAutoLogin' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'SiteNotification' => [
            'class'=>'app\components\SiteNotification'
        ],
        'EventsManager' => [
            'class'=>'app\components\EventsManager',
            'userModel' => 'webvimark\modules\UserManagement\models\User',
            'authController' => 'webvimark\modules\UserManagement\controllers\AuthController',
            'adminAuthController' => 'webvimark\modules\UserManagement\controllers\UserController'
        ],
    ],
    'params' => $params,
    'modules'=>[
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            'useEmailAsLogin' => true,
            'enableRegistration' => true,
            'emailConfirmationRequired' => true,
            'rolesAfterRegistration' => 'Reader',

            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction'=>function(yii\base\ActionEvent $event) {
            },
            'on afterRegistration' => function(webvimark\modules\UserManagement\components\UserAuthEvent $event) {
                // webvimark\modules\UserManagement\models\User::assignRole($event->user->id, 'Reader');
                if (empty($event->user->username)) {
                    $event->user->username = $event->user->email;
                    $event->user->save();
                }
                \app\commands\EmailController::notifyRegistration($event->user);
            },
        ],
    ],
];

if (YII_ENV_DEV) {
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
