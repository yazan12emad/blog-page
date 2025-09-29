<?php

namespace app\models;
use app\core\Model;

class ResetPasswordModel extends Model{




    public function saveResetToken($userData, $token, $expireDate): void
    {
        $this->getDb()->query('INSERT INTO `reset_tokens`(`user_id`, `token`, `expired_at`)VALUES (:user_id , :token , :expireDate) ',
            [
                'user_id' => $userData,
                ':token' => $token,
                ':expireDate' => $expireDate,
            ]);


    }

    public function getToken($userId)
    {
        $tokenData = $this->getDb()->query(
            'SELECT `token` FROM `reset_tokens` WHERE `user_id` = :userId  AND expired_at >= NOW() AND used = 0 ORDER BY expired_at DESC LIMIT 1',
            [
                ':userId' => $userId,
            ]
        )->fetch(\PDO::FETCH_ASSOC);


        return $tokenData;
    }

    public function markTokenAsUsed($userId, $token)
    {

        $this->getDb()->query('UPDATE `reset_tokens` SET used = 1 WHERE user_id = :userId AND token = :token',
            [
                ':userId' => $userId,
                ':token' => $token,
            ]
        );
    }
}
