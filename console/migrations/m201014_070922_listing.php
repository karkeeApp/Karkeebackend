<?php

use yii\db\Migration;

/**
 * Class m201014_070922_listing
 */
class m201014_070922_listing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `listing_gallery` (
            `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
            `account_id` bigint(20) DEFAULT NULL,
            `listing_id` bigint(20) DEFAULT NULL,
            `user_id` bigint(20) DEFAULT NULL,
            `filename` varchar(100) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            `is_primary` tinyint(4) DEFAULT '0',
            `status` tinyint(4) DEFAULT '1',
            PRIMARY KEY (`gallery_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `listing` (
            `listing_id` bigint(20) NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) DEFAULT NULL,
            `account_id` bigint(20) DEFAULT NULL,
            `title` varchar(255) DEFAULT NULL,
            `content` text,
            `status` tinyint(4) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            `approved_by` bigint(20) DEFAULT NULL,
            `confirmed_by` bigint(20) DEFAULT NULL,
            PRIMARY KEY (`listing_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201014_070922_listing cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201014_070922_listing cannot be reverted.\n";

        return false;
    }
    */
}
