<?php

use yii\db\Migration;

/**
 * Class m211118_150157_alter_settings_table
 */
class m211118_150157_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `email` VARCHAR(255) DEFAULT NULL AFTER `title`,
            ADD COLUMN `company` VARCHAR(255) DEFAULT 'Karkee' AFTER `email`,
            ADD COLUMN `logo` VARCHAR(255) DEFAULT NULL AFTER `company`,
            ADD COLUMN `contact_name` VARCHAR(255) DEFAULT 'Karkee Admin' AFTER `logo`,
            ADD COLUMN `address` VARCHAR(255) DEFAULT NULL AFTER `contact_name`,            
            ADD COLUMN `master_account_id` INT DEFAULT '0' AFTER `account_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211118_150157_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211118_150157_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
