<?php

use yii\db\Migration;

/**
 * Class m200508_142215_password_reset
 */
class m200508_142215_password_reset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `reset_code` VARCHAR(6) NULL AFTER `img_car_right`;            
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200508_142215_password_reset cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200508_142215_password_reset cannot be reverted.\n";

        return false;
    }
    */
}
