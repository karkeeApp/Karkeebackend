<?php

use yii\db\Migration;

/**
 * Class m200610_025901_carkee_member_type
 */
class m200610_025901_carkee_member_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user` 
            ADD COLUMN `carkee_member_type` TINYINT(4) NULL DEFAULT 7 AFTER `member_type`;

            UPDATE user SET carkee_member_type = member_type WHERE member_type IN (4, 5, 6) AND user_id > 0;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200610_025901_carkee_member_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200610_025901_carkee_member_type cannot be reverted.\n";

        return false;
    }
    */
}
