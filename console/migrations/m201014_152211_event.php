<?php

use yii\db\Migration;

/**
 * Class m201014_152211_event
 */
class m201014_152211_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `event` (
              `event_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `order` int(11) DEFAULT '0',
              `title` varchar(255) DEFAULT NULL,
              `content` longtext,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `created_by` bigint(20) DEFAULT NULL,
              `summary` varchar(255) DEFAULT NULL,
              `image` varchar(100) DEFAULT NULL,
              `is_public` tinyint(4) DEFAULT '0',
              `place` varchar(255) DEFAULT NULL,
              `event_time` datetime DEFAULT NULL,
              `limit` int(11) DEFAULT NULL,
              PRIMARY KEY (`event_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `event_gallery` (
              `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `event_id` bigint(20) DEFAULT NULL,
              `filename` varchar(100) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `is_primary` tinyint(4) DEFAULT '0',
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`gallery_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            ALTER TABLE `listing`
            ADD COLUMN `is_primary` TINYINT(4) NULL DEFAULT '0' AFTER `confirmed_by`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201014_152211_event cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201014_152211_event cannot be reverted.\n";

        return false;
    }
    */
}
