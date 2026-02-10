<?php

use app\core\DataBase;


require_once __DIR__ . '/../core/DataBase.php';

$dataBase = DataBase::getInstance();

foreach ($dataBase->query('SELECT id ,emailAddress  FROM UsersInformation ')->fetchAll() as $email) {
    $i =0;
        $dataBase->query('insert into email_queue (user_id , to_email , subject , body ) values (:user_id , :emailAddress , :subject , :body) ' ,
    [
        ':emailAddress' => $email['emailAddress'] ,
        ':user_id' => $email['id'] ,
        ':subject' => 'Test Email from Cron Job',
        ':body' => '<p>This is a test email sent from a cron job.</p>'
    ]);
    echo $email['emailAddress'] . "\n";
    $i++;
}

