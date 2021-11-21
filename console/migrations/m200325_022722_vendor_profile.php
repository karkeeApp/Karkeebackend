<?php

use yii\db\Migration;

/**
 * Class m200325_022722_vendor_profile
 */
class m200325_022722_vendor_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `img_vendor` VARCHAR(255) NULL AFTER `img_log_card`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200325_022722_vendor_profile cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_022722_vendor_profile cannot be reverted.\n";

        return false;
    }
    */
}
