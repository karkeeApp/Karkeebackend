<?php

use yii\db\Migration;

/**
 * Class m201014_104014_user_alter
 */
class m201014_104014_user_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `level` TINYINT NULL DEFAULT 0 AFTER `role`;

            ALTER TABLE `user` 
            ADD COLUMN `carkee_level` TINYINT(4) NULL DEFAULT 0 AFTER `level`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201014_104014_user_alter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201014_104014_user_alter cannot be reverted.\n";

        return false;
    }
    */
}
