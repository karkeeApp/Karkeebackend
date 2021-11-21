<?php

use yii\db\Migration;

/**
 * Class m210909_134531_alter_ads_table
 */
class m210909_134531_alter_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `ads` 
            ADD COLUMN `enable_ads` TINYINT DEFAULT '1' AFTER `link`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210909_134531_alter_ads_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210909_134531_alter_ads_table cannot be reverted.\n";

        return false;
    }
    */
}
