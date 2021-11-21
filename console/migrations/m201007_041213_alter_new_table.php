<?php

use yii\db\Migration;

/**
 * Class m201007_041213_alter_new_table
 */
class m201007_041213_alter_new_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `news` 
            ADD COLUMN `is_public` TINYINT NULL DEFAULT 0 AFTER `is_happening`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201007_041213_alter_new_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201007_041213_alter_new_table cannot be reverted.\n";

        return false;
    }
    */
}
