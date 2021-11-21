<?php

use yii\db\Migration;

/**
 * Class m200315_073011_vendor_founded
 */
class m200315_073011_vendor_founded extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `telephone_no` VARCHAR(20) NULL AFTER `about`;
            ALTER TABLE `user` 
            ADD COLUMN `founded_date` DATE NULL AFTER `telephone_no`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200315_073011_vendor_founded cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200315_073011_vendor_founded cannot be reverted.\n";

        return false;
    }
    */
}
