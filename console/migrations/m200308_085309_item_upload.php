<?php

use yii\db\Migration;

/**
 * Class m200308_085309_item_upload
 */
class m200308_085309_item_upload extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            DROP COLUMN `course`,
            DROP COLUMN `years`,
            DROP COLUMN `probation_period`,
            DROP COLUMN `employee_appraisal`,
            DROP COLUMN `home_number`,
            DROP COLUMN `school`,
            DROP COLUMN `nok_relationship`,
            DROP COLUMN `nok_lastname_kh`,
            DROP COLUMN `nok_firstname_kh`,
            DROP COLUMN `lastname_kh`,
            DROP COLUMN `firstname_kh`,
            DROP COLUMN `resignation_date`,
            DROP COLUMN `remittance_type`,
            DROP COLUMN `education_level`,
            DROP COLUMN `own_residential`,
            DROP COLUMN `score`,
            DROP COLUMN `nok_mobile_number`,
            DROP COLUMN `nok_home_number`,
            DROP COLUMN `nok_email`,
            DROP COLUMN `nok_current_address`,
            DROP COLUMN `nok_gender`,
            DROP COLUMN `nok_citizenship`,
            DROP COLUMN `nok_lastname`,
            DROP COLUMN `nok_firstname`,
            DROP COLUMN `nok_fullname`,
            DROP COLUMN `appraisal_year`,
            DROP COLUMN `children`,
            DROP COLUMN `end_date`,
            DROP COLUMN `other_income`,
            DROP COLUMN `commission`,
            DROP COLUMN `currency`,
            DROP COLUMN `current_salary`,
            DROP COLUMN `salary`,
            DROP COLUMN `date_employed`,
            DROP COLUMN `job_title`,
            DROP COLUMN `department`,
            DROP COLUMN `employer_contact`,
            DROP COLUMN `employer_email`,
            DROP COLUMN `employer_address`,
            DROP COLUMN `employer`,
            DROP COLUMN `marital_status`,
            DROP COLUMN `id_number`,
            DROP COLUMN `id_type`,
            DROP COLUMN `alternative_address`,
            DROP COLUMN `current_address`,
            DROP COLUMN `citizenship`;

            CREATE TABLE `item_gallery` (
              `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `item_id` bigint(20) DEFAULT NULL,
              `user_id` bigint(20) DEFAULT NULL,
              `filename` varchar(100) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `is_primary` tinyint(4) DEFAULT '0',
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`gallery_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200308_085309_item_upload cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200308_085309_item_upload cannot be reverted.\n";

        return false;
    }
    */
}
