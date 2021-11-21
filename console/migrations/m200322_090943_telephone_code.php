<?php

use yii\db\Migration;

/**
 * Class m200322_090943_telephone_code
 */
class m200322_090943_telephone_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `telephone_code` VARCHAR(5) NULL AFTER `member_type`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200322_090943_telephone_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200322_090943_telephone_code cannot be reverted.\n";

        return false;
    }
    */
}
