#!/usr/bin/php
<?php


require_once __DIR__ . '/../vendor/autoload.php';

use app\core\DataBase;
use app\models\SendEmail;

    require_once __DIR__ . '/../core/DataBase.php';
    require_once __DIR__ . '/../models/SendEmail.php';


    $DataBase = DataBase::getInstance();
    $SendEmail = SendEmail::getInstance();
        $q = new SplQueue();


    $startingTime = microtime(true);

    foreach ( $DataBase->query('SELECT emailAddress FROM UsersInformation ')->fetchAll() as $email) {
        $q->enqueue($email['emailAddress']);
        }

        $subject = 'Test Email from Cron Job';
        $body = '<p>This is a test email sent from a cron job.</p>';
        foreach ($q as $email) {
            $sendResult = $SendEmail->SendEmailBySMTP($email, $subject, $body);

            if ($sendResult) {
                echo "Email sent successfully to: $email\n";
            } else {
                echo "Failed to send email to: $email\n";
            }

        }

    $logFile = '/Applications/Blog-project-1/cron_log.txt';

    $timestamp = date('Y-m-d H:i:s');
    $message = "Cron job ran successfully at: [ $timestamp ] new code\n ";

    if (file_put_contents($logFile, $message, FILE_APPEND) === false) {
        echo "[" . date('Y-m-d H:i:s') . "] : Error writing to log file.\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] : Task completed successfully.\n";
    }

$endingTime = microtime(true);

$executionTime = $endingTime - $startingTime;


echo "Total Execution Time: " . $executionTime . " seconds\n";

exit;