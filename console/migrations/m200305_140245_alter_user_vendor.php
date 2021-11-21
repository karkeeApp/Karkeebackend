<?php

use yii\db\Migration;

/**
 * Class m200305_140245_alter_user_vendor
 */
class m200305_140245_alter_user_vendor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `is_vendor` TINYINT NULL DEFAULT 0 AFTER `step`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200305_140245_alter_user_vendor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200305_140245_alter_user_vendor cannot be reverted.\n";

        return false;
    }
    */
}
