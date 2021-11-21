<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%security_file_upload}}`.
 */
class m210908_101423_create_security_file_upload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `security_questions_fileupload` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `account_id` INT NOT NULL,
              `account_security_question_id` INT NOT NULL,
              `filename` TEXT NOT NULL,
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
        $this->dropTable('{{%security_file_upload}}');
    }
}
