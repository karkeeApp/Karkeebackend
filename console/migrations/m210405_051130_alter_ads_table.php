<?php

use yii\db\Migration;

/**
 * Class m210405_051130_alter_ads_table
 */
class m210405_051130_alter_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `ads` 
            DROP COLUMN `remove_attachment`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210405_051130_alter_ads_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210405_051130_alter_ads_table cannot be reverted.\n";

        return false;
    }
    */
}
