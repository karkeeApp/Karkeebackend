<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_sponsor}}`.
 */
class m210301_050339_create_user_sponsor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_sponsor` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT NOT NULL,
            `sponsor_id` BIGINT NULL,
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
        $this->dropTable('{{%user_sponsor}}');
    }
}
