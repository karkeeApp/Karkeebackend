<?php

use yii\db\Migration;

/**
 * Class m200406_071027_admin_role
 */
class m200406_071027_admin_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `admin` 
            ADD COLUMN `role` TINYINT NULL DEFAULT 1 AFTER `updated_at`;
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200406_071027_admin_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200406_071027_admin_role cannot be reverted.\n";

        return false;
    }
    */
}
