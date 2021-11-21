<?php

use yii\db\Migration;

/**
 * Class m210501_220538_alter_event_table
 */
class m210501_220538_alter_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event` 
            ADD COLUMN `is_closed` TINYINT DEFAULT '0' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210501_220538_alter_event_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210501_220538_alter_event_table cannot be reverted.\n";

        return false;
    }
    */
}
