<?php

use yii\db\Migration;

/**
 * Class m210411_160125_user_payment_attachment
 */
class m210411_160125_user_payment_attachment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_payment_attachment` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT DEFAULT NULL,
              `account_id` INT DEFAULT NULL,
              `payment_id` BIGINT DEFAULT NULL,
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
        echo "m210411_160125_user_payment_attachment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210411_160125_user_payment_attachment cannot be reverted.\n";

        return false;
    }
    */
}
