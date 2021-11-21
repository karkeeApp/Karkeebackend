<?php

use yii\db\Migration;

/**
 * Class m211118_163619_alter_events_table
 */
class m211118_163619_alter_events_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event` 
            CHANGE COLUMN `limit` `limit` INT DEFAULT '1000' ;
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211118_163619_alter_events_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211118_163619_alter_events_table cannot be reverted.\n";

        return false;
    }
    */
}
