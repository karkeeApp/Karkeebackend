<?php

use yii\db\Migration;

/**
 * Class m200303_103003_item
 */
class m200303_103003_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `item` (
              `item_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `title` varchar(255) DEFAULT NULL,
              `content` text,
              `limit` int(11) DEFAULT NULL,
              `status` tinyint(4) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`item_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `item_redeem` (
              `redeem_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `item_id` bigint(20) DEFAULT NULL,
              `user_id` bigint(20) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`redeem_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200303_103003_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200303_103003_item cannot be reverted.\n";

        return false;
    }
    */
}
