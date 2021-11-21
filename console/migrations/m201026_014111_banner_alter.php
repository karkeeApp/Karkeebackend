<?php

use yii\db\Migration;

/**
 * Class m201026_014111_banner_alter
 */
class m201026_014111_banner_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `banner_images` 
            ADD COLUMN `account_id` BIGINT NULL AFTER `updated_at`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201026_014111_banner_alter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201026_014111_banner_alter cannot be reverted.\n";

        return false;
    }
    */
}
