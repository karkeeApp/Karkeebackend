<?php

use yii\db\Migration;

/**
 * Class m200310_030623_account_hash_id
 */
class m200310_030623_account_hash_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `hash_id` VARCHAR(45) NULL AFTER `email`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200310_030623_account_hash_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_030623_account_hash_id cannot be reverted.\n";

        return false;
    }
    */
}
