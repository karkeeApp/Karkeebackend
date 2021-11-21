<?php

use yii\db\Migration;

/**
 * Class m210830_205200_alter_settings_table
 */
class m210830_205200_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_settings` 
            ADD COLUMN `club_code` int DEFAULT NULL AFTER `status`;
        ");
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `club_code` int DEFAULT NULL AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210830_205200_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210830_205200_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
