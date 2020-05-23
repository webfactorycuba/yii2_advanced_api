<?php

use yii\db\Migration;

class m190602_054708_create_table_config_mailer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%config_mailer}}', [
            'id' => $this->primaryKey(),
            'class' => $this->string()->notNull(),
            'host' => $this->string()->notNull(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'port' => $this->integer()->notNull()->defaultValue('25'),
            'encryption' => $this->string()->defaultValue(''),
            'timeout' => $this->integer()->defaultValue('30'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%config_mailer}}');
    }
}
