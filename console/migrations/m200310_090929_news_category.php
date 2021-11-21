<?php

use yii\db\Migration;

/**
 * Class m200310_090929_news_category
 */
class m200310_090929_news_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `news` 
            ADD COLUMN `category_id` INT NULL DEFAULT 0 AFTER `image`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200310_090929_news_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200310_090929_news_category cannot be reverted.\n";

        return false;
    }
    */
}
