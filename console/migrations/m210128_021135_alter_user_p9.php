<?php

use yii\db\Migration;

/**
 * Class m210128_021135_alter_user_p9
 */
class m210128_021135_alter_user_p9 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `insurance_date` DATE NULL AFTER `company_add_2`;

            ALTER TABLE `account` 
            CHANGE COLUMN `prefix` `prefix` VARCHAR(20) NULL DEFAULT NULL ;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210128_021135_alter_user_p9 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210128_021135_alter_user_p9 cannot be reverted.\n";

        return false;
    }
    */
}
