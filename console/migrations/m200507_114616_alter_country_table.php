<?php

use yii\db\Migration;

/**
 * Class m200507_114616_alter_country_table
 */
class m200507_114616_alter_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `country` 
            ADD COLUMN `nicename` VARCHAR(80) NOT NULL AFTER `name`,
            ADD COLUMN `iso3` VARCHAR(3) DEFAULT NULL AFTER `nicename`,
            ADD COLUMN `numcode` smallint(6) DEFAULT NULL AFTER `iso3`,
            ADD COLUMN `phonecode` int(5) NOT NULL AFTER `numcode`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200507_114616_alter_country_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200507_114616_alter_country_table cannot be reverted.\n";

        return false;
    }
    */
}
