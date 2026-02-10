<?php


require_once __DIR__ . '/../core/DataBase.php';
require_once __DIR__ . '/../models/SendEmail.php';
require_once __DIR__ . '/../vendor/autoload.php';



$dataBase = app\core\DataBase::getInstance();
$sendEmail = app\models\SendEmail::getInstance();
$queue = new SplQueue();

$startingTime = microtime(true);
$numberOfEmails = 0;
$allEmails =$dataBase->query('select * from email_queue where status ="pending"')->rowCount();

while(true){
    $emails = $dataBase->query("SELECT * FROM email_queue WHERE status = 'pending' LIMIT 20")->fetchAll();

    if(count($emails) === 0){
        break;
    }

    foreach ($emails as $email) {
        $queue->enqueue($email);
        if($sendEmail->SendEmailBySMTP($email['to_email'] , $email['subject'] , $email['body'])){
            echo "Email sent successfully to: " . $email['to_email'] . "\n";
        $dataBase->query('update email_queue set status = "sent" where id = :id' , [':id' => $email['id']]);
        $numberOfEmails++;
        echo 'Email number :  ' .$numberOfEmails;
    }
        else {
            echo "Failed to send email to: " . $email['to_email'] . "\n";
            $dataBase->query('update email_queue set status = "failed" , attempts = attempts + 1 where id = :id' , [':id' => $email['id']]);

        }
        $queue->dequeue();
    }
}

echo "All pending emails have been processed.\n";


$endTime = microtime(true);

$executionTime = $endTime - $startingTime;

echo 'Total time needed is : ' . $executionTime . " seconds\n";

exit;

