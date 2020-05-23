<?php

use yii\db\Migration;

class m190513_021113_create_table_setting_lang extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting_lang}}',
        [
            'id' => $this->primaryKey(),
            'setting_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'seo_keywords' => $this->string(),
            'main_logo' => $this->string(),
            'header_logo' => $this->string(),
            'language' => $this->string(2),
        ], $tableOptions);

        $this->addForeignKey('fk_setting1', '{{%setting_lang}}', 'setting_id', '{{%setting}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%setting_lang}}');
    }
}