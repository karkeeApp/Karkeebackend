<?php

use yii\db\Migration;

/**
 * Class m210610_140717_alter_event_table
 */
class m210610_140717_alter_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event` 
            ADD COLUMN `num_guest_brought_limit` INT DEFAULT '10' AFTER `limit`,
            ADD COLUMN `cut_off_at` datetime DEFAULT NULL AFTER `event_time`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210610_140717_alter_event_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210610_140717_alter_event_table cannot be reverted.\n";

        return false;
    }
    */
}
