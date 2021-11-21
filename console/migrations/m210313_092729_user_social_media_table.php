<?php

use yii\db\Migration;

/**
 * Class m210313_092729_user_social_media_table
 */
class m210313_092729_user_social_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE `user_social_media` (
              `id` BIGINT NOT NULL AUTO_INCREMENT,
              `user_id` BIGINT NOT NULL,
              `social_media_id` TEXT DEFAULT NULL,
              `social_media_type` tinyint(4) DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              `status` tinyint(4) DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210313_092729_user_social_media_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210313_092729_user_social_media_table cannot be reverted.\n";

        return false;
    }
    */
}
