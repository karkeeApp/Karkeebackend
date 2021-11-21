<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ads_remove_attachment}}`.
 */
class m210405_042727_create_ads_remove_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->execute("
            CREATE TABLE `ads_remove_attachment` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT NULL,
              `ads_id` BIGINT DEFAULT NULL,
              `name` VARCHAR(255) DEFAULT NULL,
              `description` TEXT DEFAULT NULL,
              `filename` VARCHAR(255) DEFAULT NULL,
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
        $this->dropTable('{{%ads_remove_attachment}}');
    }
}
