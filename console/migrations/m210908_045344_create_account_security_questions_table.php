<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account_security_questions}}`.
 */
class m210908_045344_create_account_security_questions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `account_security_questions` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `account_id` INT NOT NULL,
              `question` TEXT NOT NULL,
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
        $this->dropTable('{{%account_security_questions}}');
    }
}
