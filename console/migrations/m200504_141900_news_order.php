<?php

use yii\db\Migration;

/**
 * Class m200504_141900_news_order
 */
class m200504_141900_news_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `news` 
            ADD COLUMN `order` INT NULL DEFAULT 0 AFTER `account_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200504_141900_news_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200504_141900_news_order cannot be reverted.\n";

        return false;
    }
    */
}
