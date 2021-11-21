<?php

use yii\db\Migration;

/**
 * Class m200223_112936_user_register
 */
class m200223_112936_user_register extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `mobile_code` VARCHAR(3) NULL;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200223_112936_user_register cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200223_112936_user_register cannot be reverted.\n";

        return false;
    }
    */
}
