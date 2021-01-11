<?php

use yii\db\Migration;

class m190513_021001_create_table_setting extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'mini_header_logo' => $this->string(),
            'save_api_logs' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%setting}}');
    }
}
