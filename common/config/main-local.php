<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.8.223;dbname=entregalo',            
            'username' => 'admin',
            'password' => 'sveT1a%a',
            'charset' => 'utf8',
        ],
        /*'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=control_acceso',
            //'dsn' => 'mysql:host=localhost;dbname=entregalo',            
            'username' => 'root',
            'password' => '123456789',
            'charset' => 'utf8',
        ],*/
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.skinatech.com',
                'username' => 'pruebas@skinatech.com',
                'password' => 'ea1Ahzafeu',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
    ],
];
