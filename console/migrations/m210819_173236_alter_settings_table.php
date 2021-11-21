<?php

use yii\db\Migration;

/**
 * Class m210819_173236_alter_settings_table
 */
class m210819_173236_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `enable_ads` tinyint DEFAULT '1' AFTER `status`,
            ADD COLUMN `skip_approval` tinyint DEFAULT '1' AFTER `enable_ads`,
            ADD COLUMN `member_expiry` datetime DEFAULT NULL AFTER `skip_approval`,
            ADD COLUMN `renewal_alert` int DEFAULT '30' AFTER `member_expiry`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210819_173236_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210819_173236_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
