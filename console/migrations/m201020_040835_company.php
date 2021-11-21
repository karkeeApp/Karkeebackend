<?php

use yii\db\Migration;

/**
 * Class m201020_040835_company
 */
class m201020_040835_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `company_mobile_code` VARCHAR(3) NULL AFTER `role`,
            ADD COLUMN `company_mobile` VARCHAR(15) NULL AFTER `company_mobile_code`,
            ADD COLUMN `company_email` VARCHAR(225) NULL AFTER `company_mobile`,
            ADD COLUMN `company_country` VARCHAR(50) NULL AFTER `company_email`,
            ADD COLUMN `company_postal_code` VARCHAR(10) NULL AFTER `company_country`,
            ADD COLUMN `company_unit_no` VARCHAR(10) NULL AFTER `company_postal_code`,
            ADD COLUMN `company_add_1` VARCHAR(225) NULL AFTER `company_unit_no`,
            ADD COLUMN `company_add_2` VARCHAR(225) NULL AFTER `company_add_1`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201020_040835_company cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201020_040835_company cannot be reverted.\n";

        return false;
    }
    */
}
