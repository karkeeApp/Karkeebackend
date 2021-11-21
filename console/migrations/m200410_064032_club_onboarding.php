<?php

use yii\db\Migration;

/**
 * Class m200410_064032_club_onboarding
 */
class m200410_064032_club_onboarding extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `fullname` VARCHAR(255) NULL AFTER `telephone_code`,
            ADD COLUMN `eun` VARCHAR(20) NULL AFTER `fullname`,
            ADD COLUMN `number_of_employees` INT NULL AFTER `eun`;

            ALTER TABLE `user` 
            ADD COLUMN `img_acra` VARCHAR(255) NULL AFTER `number_of_employees`,
            ADD COLUMN `img_memorandum` VARCHAR(255) NULL AFTER `img_acra`;


            ALTER TABLE `account` 
            DROP INDEX `username` ;
            ALTER TABLE `account` 
            CHANGE COLUMN `company` `company` VARCHAR(255) NULL ;


            CREATE TABLE `user_director` (
              `director_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `fullname` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `mobile_code` varchar(5) DEFAULT NULL,
              `mobile_no` varchar(15) DEFAULT NULL,
              `is_director` tinyint(4) DEFAULT '0',
              `is_shareholder` tinyint(4) DEFAULT '0',
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `account_id` bigint(20) DEFAULT '0',
              PRIMARY KEY (`director_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200410_064032_club_onboarding cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200410_064032_club_onboarding cannot be reverted.\n";

        return false;
    }
    */
}
