<?php

use yii\db\Migration;

/**
 * Class m200324_062757_account_prefix
 */
class m200324_062757_account_prefix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `prefix` VARCHAR(5) NULL AFTER `hash_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200324_062757_account_prefix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200324_062757_account_prefix cannot be reverted.\n";

        return false;
    }
    */
}
