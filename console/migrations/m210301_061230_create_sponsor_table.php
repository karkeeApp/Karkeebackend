<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sponsor}}`.
 */
class m210301_061230_create_sponsor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `sponsor` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT NULL,
            `filename` varchar(255) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `description` longtext,
            `status` tinyint(4) DEFAULT '1',
            `category` tinyint(4) DEFAULT '1',
            `managed_by` bigint(20) DEFAULT NULL,
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
        $this->dropTable('{{%sponsor}}');
    }
}
