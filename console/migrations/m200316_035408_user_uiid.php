<?php

use yii\db\Migration;

/**
 * Class m200316_035408_user_uiid
 */
class m200316_035408_user_uiid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `ios_uiid` VARCHAR(100) NULL AFTER `about`,
            ADD COLUMN `android_uiid` VARCHAR(100) NULL AFTER `ios_uiid`;

            ALTER TABLE `user` 
            ADD COLUMN `ios_biometric` TINYINT NULL DEFAULT 0 AFTER `android_uiid`,
            ADD COLUMN `android_biometric` TINYINT NULL DEFAULT 0 AFTER `ios_biometric`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200316_035408_user_uiid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200316_035408_user_uiid cannot be reverted.\n";

        return false;
    }
    */
}
