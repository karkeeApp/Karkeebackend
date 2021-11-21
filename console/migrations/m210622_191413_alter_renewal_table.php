<?php

use yii\db\Migration;

/**
 * Class m210622_191413_alter_renewal_table
 */
class m210622_191413_alter_renewal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `renewal` 
            ADD COLUMN `expiry_id` bigint(20) DEFAULT NULL AFTER `account_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_191413_alter_renewal_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_191413_alter_renewal_table cannot be reverted.\n";

        return false;
    }
    */
}
