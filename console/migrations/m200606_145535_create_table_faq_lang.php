<?php

use yii\db\Migration;

/**
 * Class m200606_145535_create_table_faq_lang
 */
class m200606_145535_create_table_faq_lang extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%faq_lang}}', [
            'id' => $this->primaryKey(),
            'faq_id' => $this->integer()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'language' => $this->string(2)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_faq1', '{{%faq_lang}}', 'faq_id', '{{%faq}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        if (in_array('faq_lang', Yii::$app->db->schema->getTableNames())) {
            $this->dropTable('{{%faq_lang}}');
        }
    }
}
