<?php

use yii\db\Migration;

/**
 * Class m211117_064102_alter_event_attendee_table
 */
class m211117_064102_alter_event_attendee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event_attendee` 
            ADD COLUMN `filename` TEXT DEFAULT NULL AFTER `paid`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211117_064102_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211117_064102_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }
    */
}
