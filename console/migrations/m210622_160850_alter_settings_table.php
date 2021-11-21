<?php

use yii\db\Migration;

/**
 * Class m210622_160850_alter_settings_table
 */
class m210622_160850_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `title` VARCHAR(255) DEFAULT NULL AFTER `renewal_fee`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_160850_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_160850_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
