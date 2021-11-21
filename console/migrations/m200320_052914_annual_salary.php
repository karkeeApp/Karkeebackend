<?php

use yii\db\Migration;

/**
 * Class m200320_052914_annual_salary
 */
class m200320_052914_annual_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            CHANGE COLUMN `annual_salary` `annual_salary` VARCHAR(100) NULL DEFAULT NULL ;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_052914_annual_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_052914_annual_salary cannot be reverted.\n";

        return false;
    }
    */
}
