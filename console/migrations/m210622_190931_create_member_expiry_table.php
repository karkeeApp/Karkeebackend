<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%member_expiry}}`.
 */
class m210622_190931_create_member_expiry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `member_expiry` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) NOT NULL,
              `account_id` bigint(20) NOT NULL,
              `renewal_id` bigint(20) DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              `member_expiry` DATE NOT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='                       ';            
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%member_expiry}}');
    }
}
