<?php

use yii\db\Migration;

/**
 * Class m210908_094046_alter_account_security_questions_table
 */
class m210908_094046_alter_account_security_questions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account_security_questions` 
            ADD COLUMN `is_file_upload` TINYINT DEFAULT '0' AFTER `question`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210908_094046_alter_account_security_questions_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210908_094046_alter_account_security_questions_table cannot be reverted.\n";

        return false;
    }
    */
}
