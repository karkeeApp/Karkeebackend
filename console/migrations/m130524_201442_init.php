<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE `account` (
              `account_id` int(11) NOT NULL AUTO_INCREMENT,
              `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` smallint(6) NOT NULL DEFAULT '10',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              PRIMARY KEY (`account_id`),
              UNIQUE KEY `username` (`company`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            CREATE TABLE `admin` (
              `admin_id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` smallint(6) NOT NULL DEFAULT '1',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              PRIMARY KEY (`admin_id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `email` (`email`),
              UNIQUE KEY `password_reset_token` (`password_reset_token`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            CREATE TABLE `company` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` smallint(6) NOT NULL DEFAULT '10',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `email` (`email`),
              UNIQUE KEY `password_reset_token` (`password_reset_token`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            CREATE TABLE `country` (
              `country_code` char(3) NOT NULL,
              `name` varchar(225) DEFAULT NULL,
              PRIMARY KEY (`country_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

            CREATE TABLE `hr_notification` (
              `notification_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `is_read` tinyint(1) DEFAULT '0',
              `message` text,
              `created_at` datetime DEFAULT NULL,
              `title` varchar(255) DEFAULT NULL,
              `hr_id` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`notification_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `hr_setting` (
              `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `cut_off` int(11) DEFAULT NULL,
              `created_at` varchar(45) DEFAULT NULL,
              `working_days` text,
              `leave_full` tinyint(2) DEFAULT '1',
              `leave_half` tinyint(2) DEFAULT '1',
              `leave_quarter` tinyint(2) DEFAULT '1',
              `salary_date` int(11) DEFAULT NULL,
              `loan_cut_off` int(11) DEFAULT NULL,
              `salary_tax` tinyint(2) DEFAULT '1',
              PRIMARY KEY (`setting_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `hr_staff_update` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `user_id` bigint(20) DEFAULT NULL,
              `type` int(11) DEFAULT NULL,
              `before_attributes` text,
              `after_attributes` text,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '0',
              `updated_by` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `hr_user` (
              `hr_id` int(11) NOT NULL AUTO_INCREMENT,
              `account_id` int(11) NOT NULL,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `status` smallint(6) NOT NULL DEFAULT '10',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              `role` int(11) NOT NULL DEFAULT '2',
              `email` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
              PRIMARY KEY (`hr_id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `password_reset_token` (`password_reset_token`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            CREATE TABLE `leave_applications` (
              `leave_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `type` int(11) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` int(11) DEFAULT '0',
              `definition` int(11) DEFAULT NULL,
              `date_from` date DEFAULT NULL,
              `date_to` date DEFAULT NULL,
              `staff_remarks` text,
              `updated_by` bigint(20) DEFAULT NULL,
              `updated_date` datetime DEFAULT NULL,
              `hr_remarks` text,
              `day_time_type` tinyint(4) DEFAULT '0',
              `hours` float DEFAULT '0',
              `duration` float DEFAULT NULL,
              PRIMARY KEY (`leave_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `leave_holiday` (
              `holiday_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `holiday` varchar(225) DEFAULT NULL,
              `year` bigint(20) DEFAULT NULL,
              `holiday_date` date DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `created_by` bigint(20) DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `account_id` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`holiday_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `leave_summary` (
              `summary_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `leave_annual_quota` float(5,2) DEFAULT '0.00',
              `leave_casual_quota` float(5,2) DEFAULT '0.00',
              `leave_sick_quota` float(5,2) DEFAULT '0.00',
              `leave_compensatory_quota` float(5,2) DEFAULT '0.00',
              `leave_maternity_quota` float(5,2) DEFAULT '0.00',
              `leave_paternity_quota` float(5,2) DEFAULT '0.00',
              `leave_annual_total` float(5,2) DEFAULT '0.00',
              `leave_casual_total` float(5,2) DEFAULT '0.00',
              `leave_sick_total` float(5,2) DEFAULT '0.00',
              `leave_compensatory_total` float(5,2) DEFAULT '0.00',
              `leave_maternity_total` float(5,2) DEFAULT '0.00',
              `leave_paternity_total` float(5,2) DEFAULT '0.00',
              `created_at` datetime DEFAULT NULL,
              `working_days` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`summary_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `media` (
              `media_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) DEFAULT NULL,
              `filename` varchar(225) DEFAULT NULL,
              `created_by` bigint(20) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`media_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `news` (
              `news_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) DEFAULT NULL,
              `content` longtext,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `created_by` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`news_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `notification` (
              `notification_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) DEFAULT NULL,
              `message` text,
              `admin_id` bigint(20) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `recipient` text,
              `sent` tinyint(4) DEFAULT '0',
              PRIMARY KEY (`notification_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `page` (
              `page_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `name` varchar(45) DEFAULT NULL,
              `title` varchar(45) DEFAULT NULL,
              `content` longtext,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `created_admin_id` bigint(20) DEFAULT NULL,
              `updated_admin_id` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`page_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `payroll_generate` (
              `generate_id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(4) DEFAULT NULL,
              `salary_usd` decimal(11,2) DEFAULT NULL,
              `salary_khr` decimal(11,2) DEFAULT NULL,
              `commission` decimal(11,2) DEFAULT NULL,
              `less_child` decimal(11,2) DEFAULT NULL,
              `tax_payable` decimal(11,2) DEFAULT NULL,
              `less_advance` decimal(11,2) DEFAULT NULL,
              `net_salary` decimal(11,2) DEFAULT NULL,
              `account_id` decimal(4,0) DEFAULT NULL,
              `rate` varchar(45) DEFAULT NULL,
              `date` date DEFAULT NULL,
              PRIMARY KEY (`generate_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `settings` (
              `setting_id` int(11) NOT NULL AUTO_INCREMENT,
              `default_interest` float(11,2) DEFAULT NULL,
              PRIMARY KEY (`setting_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user` (
              `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
              `status` smallint(6) NOT NULL DEFAULT '1',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              `account_id` bigint(20) NOT NULL,
              `mobile` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `gender` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
              `birthday` date DEFAULT NULL,
              `citizenship` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
              `firstname` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `lastname` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `current_address` text COLLATE utf8_unicode_ci,
              `alternative_address` text COLLATE utf8_unicode_ci,
              `id_type` int(11) DEFAULT NULL,
              `id_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `marital_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
              `employer` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `employer_address` text COLLATE utf8_unicode_ci,
              `employer_email` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `employer_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `department` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `job_title` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
              `date_employed` date DEFAULT NULL,
              `salary` decimal(11,2) DEFAULT NULL,
              `currency` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `commission` decimal(11,2) DEFAULT NULL,
              `other_income` decimal(11,2) DEFAULT NULL,
              `end_date` date DEFAULT NULL,
              `children` int(11) DEFAULT NULL,
              `appraisal_year` char(4) CHARACTER SET utf8 DEFAULT NULL,
              `nok_fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_firstname` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_lastname` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_citizenship` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_gender` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_current_address` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_email` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_home_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_mobile_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `score` float(10,2) DEFAULT '0.00',
              `own_residential` tinyint(1) DEFAULT '0',
              `education_level` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `remittance_type` int(11) DEFAULT NULL,
              `resignation_date` date DEFAULT NULL,
              `firstname_kh` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `lastname_kh` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_firstname_kh` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_lastname_kh` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `nok_relationship` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `school` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
              `home_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `employee_appraisal` int(11) DEFAULT '0',
              `probation_period` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              PRIMARY KEY (`user_id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `password_reset_token` (`password_reset_token`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            CREATE TABLE `user_education` (
              `education_id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `name` varchar(45) DEFAULT NULL,
              `certificate` varchar(45) DEFAULT NULL,
              `years` varchar(45) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(1) DEFAULT '1',
              `education_level` varchar(45) DEFAULT NULL,
              `name_school` varchar(45) DEFAULT NULL,
              `course` varchar(45) DEFAULT NULL,
              PRIMARY KEY (`education_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_employment` (
              `employment_id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `company` varchar(225) DEFAULT NULL,
              `years` varchar(45) DEFAULT NULL,
              `job_description` varchar(225) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(1) DEFAULT '1',
              PRIMARY KEY (`employment_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_file` (
              `file_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `filename` varchar(225) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `type` int(11) DEFAULT NULL,
              `account_id` bigint(20) DEFAULT NULL,
              `decription` varchar(50) DEFAULT NULL,
              PRIMARY KEY (`file_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_fund` (
              `fund_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) NOT NULL,
              `credit_limit` decimal(11,2) NOT NULL,
              `credit_used` decimal(11,2) NOT NULL,
              `created_at` datetime NOT NULL,
              PRIMARY KEY (`fund_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_import` (
              `import_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `account_id` bigint(20) DEFAULT NULL,
              `filename` varchar(225) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `error_rows` text,
              `success_rows` text,
              PRIMARY KEY (`import_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_indebtedness` (
              `indebtedness_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `name` varchar(225) DEFAULT NULL,
              `amount` decimal(11,2) DEFAULT NULL,
              `outstanding` decimal(11,2) DEFAULT NULL,
              `monthly_repayment` decimal(11,2) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`indebtedness_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_loan` (
              `loan_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) NOT NULL,
              `amount` decimal(11,2) NOT NULL,
              `interest` decimal(11,2) DEFAULT NULL,
              `created_at` datetime NOT NULL,
              `status` smallint(6) NOT NULL,
              `reason` text NOT NULL,
              `approved_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `account_id` bigint(20) NOT NULL,
              `due_date` date DEFAULT NULL,
              `transfer_code` varchar(45) DEFAULT NULL,
              `currency` char(3) DEFAULT NULL,
              `date_transfer` date DEFAULT NULL,
              `remittance_type` int(11) DEFAULT NULL,
              `attachment` varchar(100) DEFAULT NULL,
              `transfer_amount` decimal(11,2) DEFAULT NULL,
              PRIMARY KEY (`loan_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_loan_payments` (
              `payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `loan_id` bigint(20) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `amount` decimal(11,2) DEFAULT NULL,
              `status` tinyint(1) DEFAULT '1',
              `user_id` bigint(20) DEFAULT NULL,
              PRIMARY KEY (`payment_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_notification` (
              `notification_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `is_read` tinyint(1) DEFAULT '0',
              `message` text,
              `created_at` datetime DEFAULT NULL,
              `title` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`notification_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

            CREATE TABLE `user_transaction` (
              `txn_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) DEFAULT NULL,
              `amount` decimal(11,2) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `type` int(11) DEFAULT NULL,
              `class_name` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`txn_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

        ");
    }

    public function down()
    {
    }
}
