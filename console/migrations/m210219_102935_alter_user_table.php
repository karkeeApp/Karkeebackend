<?php

use yii\db\Migration;

/**
 * Class m210219_102935_alter_user_table
 */
class m210219_102935_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `company_logo` VARCHAR(255) DEFAULT NULL AFTER `company_add_2`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_102935_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_102935_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
