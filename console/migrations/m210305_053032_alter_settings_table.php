<?php

use yii\db\Migration;

/**
 * Class m210305_053032_alter_settings_table
 */
class m210305_053032_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `content` LONGTEXT DEFAULT NULL AFTER `default_interest`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210305_053032_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210305_053032_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
