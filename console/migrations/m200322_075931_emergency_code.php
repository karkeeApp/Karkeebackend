<?php

use yii\db\Migration;

/**
 * Class m200322_075931_emergency_code
 */
class m200322_075931_emergency_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `emergency_code` VARCHAR(5) NULL AFTER `contact_person`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200322_075931_emergency_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200322_075931_emergency_code cannot be reverted.\n";

        return false;
    }
    */
}
