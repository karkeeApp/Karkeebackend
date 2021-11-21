<?php

use yii\db\Migration;

/**
 * Class m210412_085028_alter_ads_table
 */
class m210412_085028_alter_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `ads` 
            ADD COLUMN `link` TEXT DEFAULT NULL AFTER `image`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210412_085028_alter_ads_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210412_085028_alter_ads_table cannot be reverted.\n";

        return false;
    }
    */
}
