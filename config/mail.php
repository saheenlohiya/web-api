<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'email-smtp.us-east-1.amazonaws.com',
        'username' => 'AKIAIYOOR4KG7FX5AFWA',
        'password' => 'ArcDUvHKOYBeL0GTOTKZWOMAszFes5VITPr0M4xxTZ1s',
        'port' => '587',
        'encryption' => 'tls',
    ],
];