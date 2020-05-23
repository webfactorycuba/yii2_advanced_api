<?php

use yii\db\Migration;

class m190203_224323_create_table_language extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%language}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%language}}');
    }
}
