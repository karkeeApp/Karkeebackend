<?php

use yii\db\Migration;

/**
 * Class m210224_083206_renewal
 */
class m210224_083206_renewal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `renewal` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `filename` varchar(225) DEFAULT NULL,
              `updated_by` bigint(20) DEFAULT NULL,
              `status` tinyint(4) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='                       ';            
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_083206_renewal cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_083206_renewal cannot be reverted.\n";

        return false;
    }
    */
}
