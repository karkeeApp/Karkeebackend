<?php

use yii\db\Migration;

/**
 * Class m200908_102227_user_role
 */
class m200908_102227_user_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `role` TINYINT NULL AFTER `confirmed_by`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_102227_user_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200908_102227_user_role cannot be reverted.\n";

        return false;
    }
    */
}
