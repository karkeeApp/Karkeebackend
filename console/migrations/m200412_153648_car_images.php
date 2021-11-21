<?php

use yii\db\Migration;

/**
 * Class m200412_153648_car_images
 */
class m200412_153648_car_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `img_car_front` VARCHAR(255) NULL AFTER `img_memorandum`,
            ADD COLUMN `img_car_back` VARCHAR(255) NULL AFTER `img_car_front`,
            ADD COLUMN `img_car_left` VARCHAR(255) NULL AFTER `img_car_back`,
            ADD COLUMN `img_car_right` VARCHAR(255) NULL AFTER `img_car_left`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200412_153648_car_images cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200412_153648_car_images cannot be reverted.\n";

        return false;
    }
    */
}
