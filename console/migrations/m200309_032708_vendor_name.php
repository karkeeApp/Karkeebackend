<?php

use yii\db\Migration;

/**
 * Class m200309_032708_vendor_name
 */
class m200309_032708_vendor_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `vendor_name` VARCHAR(255) NULL AFTER `is_vendor`; 

            ALTER TABLE `user` 
            ADD COLUMN `vendor_description` VARCHAR(255) NULL AFTER `vendor_name`,
            ADD COLUMN `about` TEXT NULL AFTER `vendor_description`;
          
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200309_032708_vendor_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200309_032708_vendor_name cannot be reverted.\n";

        return false;
    }
    */
}
