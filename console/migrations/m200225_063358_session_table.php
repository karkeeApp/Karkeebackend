<?php

use yii\db\Migration;

/**
 * Class m200225_063358_session_table
 */
class m200225_063358_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `company_full_name` VARCHAR(255) NULL AFTER `updated_at`;

            ALTER TABLE `account` 
            ADD COLUMN `address` VARCHAR(255) NULL AFTER `company_full_name`,
            ADD COLUMN `contact_name` VARCHAR(255) NULL AFTER `address`,
            ADD COLUMN `email` VARCHAR(255) NULL AFTER `contact_name`;

            CREATE TABLE `session` (
              `id` char(64) NOT NULL,
              `expire` int(11) DEFAULT NULL,
              `data` blob,
              `user_id` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

            CREATE TABLE `document` (
              `doc_id` BIGINT NOT NULL AUTO_INCREMENT,
              `filename` VARCHAR(225) NULL,
              `user_id` BIGINT NULL,
              `created_at` DATETIME NULL,
              `updated_at` DATETIME NULL,
              `status` TINYINT NULL DEFAULT 1,
              `type` TINYINT NULL,
              PRIMARY KEY (`doc_id`))
            COMMENT = '     ';

            ALTER TABLE `user` 
            ADD COLUMN `img_profile` VARCHAR(255) NULL AFTER `relationship`,
            ADD COLUMN `img_nric` VARCHAR(255) NULL AFTER `img_profile`,
            ADD COLUMN `img_insurance` VARCHAR(255) NULL AFTER `img_nric`,
            ADD COLUMN `img_authorization` VARCHAR(255) NULL AFTER `img_insurance`,
            ADD COLUMN `img_log_card` VARCHAR(255) NULL AFTER `img_authorization`;

            ALTER TABLE `user` 
            ADD COLUMN `transfer_no` VARCHAR(45) NULL AFTER `img_log_card`,
            ADD COLUMN `transfer_banking_nick` VARCHAR(45) NULL AFTER `transfer_no`,
            ADD COLUMN `transfer_date` DATE NULL AFTER `transfer_banking_nick`,
            ADD COLUMN `transfer_amount` DECIMAL(11,2) NULL AFTER `transfer_date`,
            ADD COLUMN `transfer_screenshot` VARCHAR(255) NULL AFTER `transfer_amount`;

            ALTER TABLE `user` 
            ADD COLUMN `step` TINYINT NULL DEFAULT 1 AFTER `transfer_screenshot`;

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200225_063358_session_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200225_063358_session_table cannot be reverted.\n";

        return false;
    }
    */
}
