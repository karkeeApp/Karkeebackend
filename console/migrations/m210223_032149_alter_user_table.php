<?php

use yii\db\Migration;

/**
 * Class m210223_032149_alter_user_table
 */
class m210223_032149_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `brand_synopsis` LONGTEXT DEFAULT NULL AFTER `company_logo`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_032149_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_032149_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
