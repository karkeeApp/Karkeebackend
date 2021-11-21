<?php

use yii\db\Migration;

/**
 * Class m210622_140142_alter_settings_table
 */
class m210622_140142_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `renewal_fee` DOUBLE DEFAULT '100' AFTER `content`,
            ADD COLUMN `created_at` datetime DEFAULT NULL AFTER `renewal_fee`,
            ADD COLUMN `updated_at` datetime DEFAULT NULL AFTER `created_at`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_140142_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_140142_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
