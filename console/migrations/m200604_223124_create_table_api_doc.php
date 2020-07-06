<?php

use yii\db\Migration;

class m200604_223124_create_table_api_doc extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%api_doc}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(),
                'type' => $this->string(),
                'get' => $this->boolean(),
                'post' => $this->boolean(),
                'put' => $this->boolean(),
                'delete' => $this->boolean(),
                'options' => $this->boolean(),
                'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%api_doc}}');
    }
}
