<?php
if (!defined('SECURE_BOOT')) {
    header('Location: ../');
    die('Direct access is not permitted.');
}

return [
    'DataBase' => [
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'test',
        'charset' => 'utf8mb4',
    ],
    'SMTP' => [
        'Host' => 'sandbox.smtp.mailtrap.io',
        'SMTPAuth' => true,
        'Username' => 'bba99b64486755',
        'Password' => 'c7e0becec9c2d9',
        'Port    ' => 2525,
    ],
];