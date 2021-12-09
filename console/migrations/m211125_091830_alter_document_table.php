<?php

use yii\db\Migration;

/**
 * Class m211125_091830_alter_document_table
 */
class m211125_091830_alter_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `document` 
            ADD COLUMN `account_id` INT DEFAULT '0' AFTER `user_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211125_091830_alter_document_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211125_091830_alter_document_table cannot be reverted.\n";

        return false;
    }
    */
}
