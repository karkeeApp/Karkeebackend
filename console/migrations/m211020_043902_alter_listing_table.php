<?php

use yii\db\Migration;

/**
 * Class m211020_043902_alter_listing_table
 */
class m211020_043902_alter_listing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `listing` 
            ADD COLUMN `image` varchar(100) DEFAULT NULL AFTER `content`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211020_043902_alter_listing_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211020_043902_alter_listing_table cannot be reverted.\n";

        return false;
    }
    */
}
