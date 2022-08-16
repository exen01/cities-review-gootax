<?php

use yii\db\Migration;

/**
 * Class m220816_102337_update_user_registration
 */
class m220816_102337_update_user_registration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'auth_key', $this->string(32)->notNull());
        $this->addColumn('{{%user}}', 'password_reset_token', $this->string(255)->unique());
        $this->addColumn('{{%user}}', 'verification_token', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'auth_key');
        $this->dropColumn('{{%user}}', 'password_reset_token');
        $this->dropColumn('{{%user}}', 'verification_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220816_102337_update_user_registration cannot be reverted.\n";

        return false;
    }
    */
}
