<?php

use yii\db\Migration;

/**
 * Class m200821_142521_admin_permission
 */
class m200821_142521_admin_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `item` 
            ADD COLUMN `approved_by` BIGINT NULL AFTER `amount`,
            ADD COLUMN `confirmed_by` BIGINT NULL AFTER `approved_by`;

            ALTER TABLE `user` 
            ADD COLUMN `approved_by` BIGINT NULL,
            ADD COLUMN `confirmed_by` BIGINT NULL AFTER `approved_by`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200821_142521_admin_permission cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200821_142521_admin_permission cannot be reverted.\n";

        return false;
    }
    */
}
