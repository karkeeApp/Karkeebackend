<?php

use yii\db\Migration;

/**
 * Class m201021_021604_user_approved_at
 */
class m201021_021604_user_approved_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `approved_at` DATETIME NULL AFTER `carkee_level`;

            ALTER TABLE `user` 
            CHANGE COLUMN `mobile_code` `mobile_code` VARCHAR(5) NULL DEFAULT NULL ;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201021_021604_user_approved_at cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201021_021604_user_approved_at cannot be reverted.\n";

        return false;
    }
    */
}
