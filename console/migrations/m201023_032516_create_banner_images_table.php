<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner_images}}`.
 */
class m201023_032516_create_banner_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `banner_images` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `banner_id` int(11) DEFAULT NULL,
            `title` varchar(255) DEFAULT NULL,
            `description` longtext,
            `filename` varchar(100) DEFAULT NULL,
            `status` tinyint(4) DEFAULT '1',
            `uploaded_by` bigint(20) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%banner_images}}');
    }
}
