<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%member_security_answers}}`.
 */
class m210908_045521_create_member_security_answers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `member_security_answers` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `account_membership_id` INT NOT NULL,
              `account_id` INT NOT NULL,
              `user_id` INT NOT NULL,
              `question_id` INT NOT NULL,
              `answer` TEXT DEFAULT NULL,
              `status` tinyint DEFAULT '1',
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%member_security_answers}}');
    }
}
