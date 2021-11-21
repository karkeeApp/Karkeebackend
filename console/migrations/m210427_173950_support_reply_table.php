<?php

use yii\db\Migration;

/**
 * Class m210427_173950_support_reply_table
 */
class m210427_173950_support_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `support_reply` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `support_id` BIGINT DEFAULT NULL,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT '0',
              `title` VARCHAR(255) DEFAULT NULL,
              `message` TEXT DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210427_173950_support_reply_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210427_173950_support_reply_table cannot be reverted.\n";

        return false;
    }
    */
}
