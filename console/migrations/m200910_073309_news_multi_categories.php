<?php

use yii\db\Migration;

/**
 * Class m200910_073309_news_multi_categories
 */
class m200910_073309_news_multi_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            ALTER TABLE `news` 
            ADD COLUMN `is_news` TINYINT NULL DEFAULT 0 AFTER `category_id`,
            ADD COLUMN `is_guest` TINYINT NULL DEFAULT 0 AFTER `is_news`,
            ADD COLUMN `is_trending` TINYINT NULL DEFAULT 0 AFTER `is_guest`,
            ADD COLUMN `is_event` TINYINT NULL DEFAULT 0 AFTER `is_trending`,
            ADD COLUMN `is_happening` TINYINT NULL DEFAULT 0 AFTER `is_event`;
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200910_073309_news_multi_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200910_073309_news_multi_categories cannot be reverted.\n";

        return false;
    }
    */
}
