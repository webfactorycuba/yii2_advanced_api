<?php

use yii\db\Migration;

/**
 * Class m200606_145519_create_table_faq_group_lang
 */
class m200606_145519_create_table_faq_group_lang extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%faq_group_lang}}', [
            'id' => $this->primaryKey(),
            'faq_group_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'language' => $this->string(2)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_faq_group1', '{{%faq_group_lang}}', 'faq_group_id', '{{%faq_group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%faq_group_lang}}');
    }
}
