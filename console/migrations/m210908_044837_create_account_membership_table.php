<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account_membership}}`.
 */
class m210908_044837_create_account_membership_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `account_membership` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `account_id` INT NOT NULL,
              `club_code` VARCHAR(20) DEFAULT NULL,
              `filename` TEXT DEFAULT NULL,
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
        $this->dropTable('{{%account_membership}}');
    }
}
