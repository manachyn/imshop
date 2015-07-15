<?php
/**
 * Application configuration shared by all test types
 */

use Helper\Mail;

return [
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/codeception/fixtures',
            'templatePath' => '@tests/codeception/templates',
            'namespace' => 'tests\codeception\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=imshop_tests',
        ],
        'mailer' => [
            'useFileTransport' => true,
            'on afterSend' => function ($event) {
                if ($event->isSuccessful) {
                    Mail::$mails[] = $event->message;
                }
            },
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
