<?php

use yii\db\Migration;

/**
 * Class m200322_082913_member_type
 */
class m200322_082913_member_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `member_type` TINYINT NULL DEFAULT 1 AFTER `founded_date`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200322_082913_member_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200322_082913_member_type cannot be reverted.\n";

        return false;
    }
    */
}
