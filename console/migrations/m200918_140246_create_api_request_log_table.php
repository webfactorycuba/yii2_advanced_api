<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_request_log}}`.
 */
class m200918_140246_create_api_request_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%api_request_log}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->string(250),
            'user_agent' => $this->string(255),
            'method' => $this->string(10),
            'headers' => $this->text(),
            'body' => $this->text(),
            'ip' => $this->string(32),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('api_request_log', Yii::$app->db->schema->getTableNames())) {
            $this->dropTable('{{%api_request_log}}');
        }
    }
}
