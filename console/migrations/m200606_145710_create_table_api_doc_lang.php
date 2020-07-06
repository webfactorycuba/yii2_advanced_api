<?php

use yii\db\Migration;

/**
 * Class m200606_145710_create_table_api_doc_lang
 */
class m200606_145710_create_table_api_doc_lang extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%api_doc_lang}}', [
            'id' => $this->primaryKey(),
            'api_doc_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'language' => $this->string(2)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_api_doc1', '{{%api_doc_lang}}', 'api_doc_id', '{{%api_doc}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        if (in_array('api_doc_lang', Yii::$app->db->schema->getTableNames())) {
            $this->dropTable('{{%api_doc_lang}}');
        }
    }
}
