<?php

use yii\db\Migration;

/**
 * Class m220815_144033_init
 */
class m220815_144033_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'date_create' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%review}}', [
            'id' => $this->primaryKey(),
            'id_city' => $this->integer(),
            'title' => $this->string(100)->notNull(),
            'text' => $this->string(255)->notNull(),
            'rating' => $this->integer(1)->notNull(),
            'img' => $this->string(),
            'id_author' => $this->integer()->notNull(),
            'date_create' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string(128)->notNull(),
            'email' => $this->string(128)->unique()->notNull(),
            'phone' => $this->string(128),
            'date_create' => $this->integer()->notNull(),
            'password' => $this->string(128)->notNull()
        ]);

        $this->addForeignKey(
            'FK_review_city',
            '{{%review}}',
            'id_city',
            '{{%city}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'FK_review_user',
            '{{%review}}',
            'id_author',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->insert('{{%user}}', [
            'fio' => 'demo',
            'email' => 'demo@test.com',
            'phone' => '1234567899',
            'date_create' => time(),
            'password' => '$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%review}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220815_103937_init cannot be reverted.\n";

        return false;
    }
    */
}
