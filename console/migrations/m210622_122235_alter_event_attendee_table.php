<?php

use yii\db\Migration;

/**
 * Class m210622_122235_alter_event_attendee_table
 */
class m210622_122235_alter_event_attendee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event_attendee` 
            ADD COLUMN `paid` DOUBLE DEFAULT '0' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_122235_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_122235_alter_event_attendee_table cannot be reverted.\n";

        return false;
    }
    */
}
