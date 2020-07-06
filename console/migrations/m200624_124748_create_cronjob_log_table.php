<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%conjob_log}}`.
 */
class m200624_124748_create_cronjob_log_table extends Migration
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

        $this->createTable('{{%cronjob_log}}', [
            'id' => $this->primaryKey(),
            'cronjob_task_id' => $this->integer(10),
            'message' => $this->text(),
            'execution_date' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_cronjob_log_cronjob_task',
            '{{%cronjob_log}}',
            'cronjob_task_id',
            '{{%cronjob_task}}',
            'id',
            'SET NULL',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('cronjob_log', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table cronjob_log ...";
            $this->dropTable('{{%cronjob_log}}');
        }
    }
}
