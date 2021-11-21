<?php

use yii\db\Migration;

/**
 * Class m200610_143519_carkee_settings
 */
class m200610_143519_carkee_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `longitude` DECIMAL(10,8) NULL,
            ADD COLUMN `latitude` DECIMAL(10,8) NULL AFTER `longitude`,
            ADD COLUMN `member_expire` DATE NULL AFTER `latitude`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200610_143519_carkee_settings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200610_143519_carkee_settings cannot be reverted.\n";

        return false;
    }
    */
}
