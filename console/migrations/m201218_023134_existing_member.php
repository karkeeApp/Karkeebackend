<?php

use yii\db\Migration;

/**
 * Class m201218_023134_existing_member
 */
class m201218_023134_existing_member extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_existing` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL,
              `plate_no` varchar(10) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `account_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201218_023134_existing_member cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201218_023134_existing_member cannot be reverted.\n";

        return false;
    }
    */
}
