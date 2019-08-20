<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'email-smtp.us-east-1.amazonaws.com',
        'username' => 'AKIA2N6LJNL5H3KK7RMT',
        'password' => 'BLea1cOF3vFMBk2SvGKYxzqMrtNzMuRSdh6eFW46a6lr',
        'port' => '587',
        'encryption' => 'tls',
    ],
];