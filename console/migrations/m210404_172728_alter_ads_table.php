<?php

use yii\db\Migration;

/**
 * Class m210404_172728_alter_ads_table
 */
class m210404_172728_alter_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `ads` 
            ADD COLUMN `remove_attachment` VARCHAR(255) DEFAULT NULL AFTER `image`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210404_172728_alter_ads_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210404_172728_alter_ads_table cannot be reverted.\n";

        return false;
    }
    */
}
