<?php

use yii\db\Migration;

/**
 * Class m210422_185308_support_table
 */
class m210422_185308_support_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `support` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT '0',
              `title` VARCHAR(255) DEFAULT NULL,
              `description` TEXT DEFAULT NULL,
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
        echo "m210422_185308_support_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_185308_support_table cannot be reverted.\n";

        return false;
    }
    */
}
