<?php

use yii\db\Migration;

/**
 * Class m210826_201335_alter_account_table
 */
class m210826_201335_alter_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `club_code` int DEFAULT NULL AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210826_201335_alter_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210826_201335_alter_account_table cannot be reverted.\n";

        return false;
    }
    */
}
