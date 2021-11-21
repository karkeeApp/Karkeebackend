<?php

use yii\db\Migration;

/**
 * Class m200224_141540_alter_user_onboarding
 */
class m200224_141540_alter_user_onboarding extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `country` VARCHAR(50) NULL AFTER `mobile_code`,
            ADD COLUMN `postal_code` VARCHAR(10) NULL AFTER `country`,
            ADD COLUMN `unit_no` VARCHAR(10) NULL AFTER `postal_code`,
            ADD COLUMN `add_1` VARCHAR(255) NULL AFTER `unit_no`,
            ADD COLUMN `add_2` VARCHAR(255) NULL AFTER `add_1`,
            ADD COLUMN `nric` VARCHAR(15) NULL AFTER `add_2`,
            ADD COLUMN `profession` VARCHAR(255) NULL AFTER `nric`,
            ADD COLUMN `company` VARCHAR(255) NULL AFTER `profession`,
            ADD COLUMN `annual_salary` DECIMAL(11,2) NULL AFTER `company`;

            ALTER TABLE `user` 
            ADD COLUMN `chasis_number` VARCHAR(50) NULL AFTER `annual_salary`,
            ADD COLUMN `plate_no` VARCHAR(10) NULL AFTER `chasis_number`,
            ADD COLUMN `car_model` VARCHAR(50) NULL AFTER `plate_no`,
            ADD COLUMN `registration_code` VARCHAR(45) NULL AFTER `car_model`,
            ADD COLUMN `are_you_owner` TINYINT NULL AFTER `registration_code`,
            ADD COLUMN `contact_person` VARCHAR(255) NULL AFTER `are_you_owner`,
            ADD COLUMN `emergency_no` VARCHAR(20) NULL AFTER `contact_person`,
            ADD COLUMN `relationship` TINYINT NULL AFTER `emergency_no`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200224_141540_alter_user_onboarding cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200224_141540_alter_user_onboarding cannot be reverted.\n";

        return false;
    }
    */
}
