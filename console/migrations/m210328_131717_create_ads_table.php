<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ads}}`.
 */
class m210328_131717_create_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `ads` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT NULL,
              `name` VARCHAR(255) DEFAULT NULL,
              `description` TEXT DEFAULT NULL,
              `image` VARCHAR(255) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ads}}');
    }
}
