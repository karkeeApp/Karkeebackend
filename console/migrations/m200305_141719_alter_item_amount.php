<?php

use yii\db\Migration;

/**
 * Class m200305_141719_alter_item_amount
 */
class m200305_141719_alter_item_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `item` 
            ADD COLUMN `amount` DECIMAL(11,2) NULL DEFAULT 0 AFTER `updated_at`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200305_141719_alter_item_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200305_141719_alter_item_amount cannot be reverted.\n";

        return false;
    }
    */
}
