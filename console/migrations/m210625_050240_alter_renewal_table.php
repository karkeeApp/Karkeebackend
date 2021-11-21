<?php

use yii\db\Migration;

/**
 * Class m210625_050240_alter_renewal_table
 */
class m210625_050240_alter_renewal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `renewal` 
            ADD COLUMN `paid` DOUBLE DEFAULT NULL AFTER `expiry_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210625_050240_alter_renewal_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210625_050240_alter_renewal_table cannot be reverted.\n";

        return false;
    }
    */
}
