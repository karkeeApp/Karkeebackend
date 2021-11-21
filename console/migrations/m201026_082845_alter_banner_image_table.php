<?php

use yii\db\Migration;

/**
 * Class m201026_082845_alter_banner_image_table
 */
class m201026_082845_alter_banner_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `banner_images` 
            CHANGE COLUMN `description` `content` LONGTEXT NULL AFTER `title`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201026_082845_alter_banner_image_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201026_082845_alter_banner_image_table cannot be reverted.\n";

        return false;
    }
    */
}
