<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_settings}}`.
 */
class m210819_190232_create_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_settings` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) NOT NULL,
              `account_id` bigint(20) NOT NULL,
              `enable_ads` tinyint DEFAULT '1',
              `skip_approval` tinyint DEFAULT '1',
              `renewal_alert` int DEFAULT '30',
              `status` tinyint(4) DEFAULT '1',
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
        $this->dropTable('{{%user_settings}}');
    }
}
