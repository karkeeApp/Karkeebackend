<?php

use yii\db\Migration;

/**
 * Class m210411_153226_user_payment_table
 */
class m210411_153226_user_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_payment` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT NULL,
              `amount` DECIMAL(10,3) DEFAULT '0',
              `name` VARCHAR(255) DEFAULT NULL,
              `description` TEXT DEFAULT NULL,
              `filename` VARCHAR(255) DEFAULT NULL,
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
        echo "m210411_153226_user_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210411_153226_user_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
