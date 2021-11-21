<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_attendee}}`.
 */
class m201015_091859_create_event_attendee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `event_attendee` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `event_id` bigint(20) DEFAULT NULL,
              `user_id` bigint(20) DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_attendee}}');
    }
}
