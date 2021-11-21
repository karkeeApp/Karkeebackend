<?php

use yii\db\Migration;

/**
 * Class m210908_071316_alter_account_membership_table
 */
class m210908_071316_alter_account_membership_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account_membership` 
            ADD COLUMN `description` TEXT DEFAULT NULL AFTER `filename`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210908_071316_alter_account_membership_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210908_071316_alter_account_membership_table cannot be reverted.\n";

        return false;
    }
    */
}
