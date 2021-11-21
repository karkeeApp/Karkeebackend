<?php

use yii\db\Migration;

/**
 * Class m210422_123454_alter_user_fcm_table
 */
class m210422_123454_alter_user_fcm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_fcm_token` 
            ADD COLUMN `account_id` INT DEFAULT '0' AFTER `user_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210422_123454_alter_user_fcm_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_123454_alter_user_fcm_table cannot be reverted.\n";

        return false;
    }
    */
}
