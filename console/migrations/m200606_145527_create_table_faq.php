<?php

use yii\db\Migration;

/**
 * Class m200606_145527_create_table_faq
 */
class m200606_145527_create_table_faq extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%faq}}', [
            'id' => $this->primaryKey(),
            'faq_group_id' => $this->integer()->notNull(),
            'image' => $this->string(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue('1'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_faq_group2', '{{%faq}}', 'faq_group_id', '{{%faq_group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        if (in_array('faq', Yii::$app->db->schema->getTableNames())) {
            $this->dropTable('{{%faq}}');
        }
    }
}
