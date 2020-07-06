<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%conjob_task}}`.
 */
class m200624_124740_create_cronjob_task_table extends Migration
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

        $this->createTable('{{%cronjob_task}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (in_array('cronjob_task', Yii::$app->db->schema->getTableNames())) {
            echo "deleting table cronjob_task ...";
            $this->dropTable('{{%cronjob_task}}');
        }
    }
}
