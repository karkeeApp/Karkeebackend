<?php

use yii\db\Migration;

/**
 * Class m200505_123924_watchdog
 */
class m200505_123924_watchdog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `watchdog` (
              `wid` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL DEFAULT '0',
              `message` longtext NOT NULL,
              `variables` longtext NOT NULL,
              `referer` varchar(128) DEFAULT NULL,
              `hostname` varchar(128) DEFAULT NULL,
              `created_at` datetime NOT NULL,
              `account_id` int(11) DEFAULT '0',
              PRIMARY KEY (`wid`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;            
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200505_123924_watchdog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200505_123924_watchdog cannot be reverted.\n";

        return false;
    }
    */
}
