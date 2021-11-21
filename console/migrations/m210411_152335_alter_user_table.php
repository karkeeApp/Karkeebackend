<?php

use yii\db\Migration;

/**
 * Class m210411_152335_alter_user_table
 */
class m210411_152335_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `is_premium` TINYINT DEFAULT '0' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210411_152335_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210411_152335_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
