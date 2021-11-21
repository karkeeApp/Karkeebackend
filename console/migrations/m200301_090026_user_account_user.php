<?php

use yii\db\Migration;

/**
 * Class m200301_090026_user_account_user
 */
class m200301_090026_user_account_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account_user` 
            DROP INDEX `username` ;
            ;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200301_090026_user_account_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200301_090026_user_account_user cannot be reverted.\n";

        return false;
    }
    */
}
