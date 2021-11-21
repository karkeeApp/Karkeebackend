<?php

use yii\db\Migration;

/**
 * Class m200310_033420_news
 */
class m200310_033420_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `news` (
              `news_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `title` varchar(255) DEFAULT NULL,
              `content` longtext,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `created_by` bigint(20) DEFAULT NULL,
              `summary` varchar(255) DEFAULT NULL,
              `image` varchar(100) DEFAULT NULL,
              PRIMARY KEY (`news_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200310_033420_news cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_033420_news cannot be reverted.\n";

        return false;
    }
    */
}
