<?php

use yii\db\Migration;

/**
 * Class m200922_145900_news_gallery
 */
class m200922_145900_news_gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `news_gallery` (
              `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `news_id` bigint(20) DEFAULT NULL,
              `filename` varchar(100) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `is_primary` tinyint(4) DEFAULT '0',
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`gallery_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200922_145900_news_gallery cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200922_145900_news_gallery cannot be reverted.\n";

        return false;
    }
    */
}
