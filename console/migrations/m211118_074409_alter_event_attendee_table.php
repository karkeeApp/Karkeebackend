<?php

use yii\db\Migration;

/**
 * Class m211118_074409_alter_event_attendee_table
 */
class m211118_074409_alter_event_attendee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event_attendee` 
            ADD COLUMN `name` VARCHAR(255) DEFAULT NULL AFTER `filename`,
            ADD COLUMN `description` TEXT DEFAULT NULL AFTER `name`;
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211118_074409_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211118_074409_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }
    */
}
