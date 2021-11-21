<?php

use yii\db\Migration;

/**
 * Class m210622_122207_alter_event_table
 */
class m210622_122207_alter_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `event` 
            ADD COLUMN `is_paid` TINYINT DEFAULT '0' AFTER `event_time`,
            ADD COLUMN `event_fee` DOUBLE DEFAULT NULL AFTER `is_paid`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_122207_alter_event_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_122207_alter_event_table cannot be reverted.\n";

        return false;
    }
    */
}
