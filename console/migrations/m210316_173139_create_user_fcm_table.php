<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_fcm}}`.
 */
class m210316_173139_create_user_fcm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_fcm_token` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT NOT NULL,
              `fcm_token` TEXT DEFAULT NULL,
              `fcm_topics` VARCHAR(255) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_fcm}}');
    }
}
