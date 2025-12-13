<?php

namespace app;

return
    [
        'DataBase' =>
            [
                'host' => 'localhost',
                'port' => 3306,
                'dbname' => 'test',
                'charset' => 'utf8mb4',
            ],

        'SMTP' =>
            [
                'Host' => 'sandbox.smtp.mailtrap.io',
                'SMTPAuth' => true,
                'Username' => 'bba99b64486755',
                'Password' => 'c7e0becec9c2d9',
                'Port' => 2525,
            ],

        'captcha' =>
            [
                'secretKey' =>
                    '6LfNiAAsAAAAANEvg8Mf3UzbwxZE_quOdK7IfL_s'
            ]
    ];