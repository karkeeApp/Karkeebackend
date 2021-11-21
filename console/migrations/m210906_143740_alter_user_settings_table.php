<?php

use yii\db\Migration;

/**
 * Class m210906_143740_alter_user_settings_table
 */
class m210906_143740_alter_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_settings` 
            ADD COLUMN `verification_code` VARCHAR(10) DEFAULT NULL AFTER `status`,
            ADD COLUMN `is_verified` TINYINT DEFAULT '0' AFTER `verification_code`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210906_143740_alter_user_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210906_143740_alter_user_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
