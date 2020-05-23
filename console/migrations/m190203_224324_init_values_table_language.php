<?php

use yii\db\Migration;
use backend\models\i18n\Language;

class m190203_224324_init_values_table_language extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
     //Insert data to language ES
        $language_1_exists = Language::findOne(1);

        if(isset($language_1_exists) && !empty($language_1_exists))
        {
            echo "      > Language ES already created, not inserted data again.\n";
        }
        else
        {
            $language_es = new Language();
            $language_es->id = 1;
            $language_es->code = 'es';
            $language_es->image = 'fh7Vv8mukRdvp5qQLgfIQrcM6GMSvfeg.svg';
            $language_es->status = 1;

            if($language_es->save())
            {
                echo "      > Language ES has been created successfully.\n";
            }
            else
            {
                echo "      > m190203_224324_init_values_table_language cannot create datas for language ES.\n";
                return false;
            }
        }

    //Insert data to language EN
        $language_2_exists = Language::findOne(2);

        if(isset($language_2_exists) && !empty($language_2_exists))
        {
            echo "      > Language ES already created, not inserted data again.\n";
        }
        else
        {
            $language_en = new Language();
            $language_en->id = 2;
            $language_en->code = 'en';
            $language_en->image = 'O15f8QV2eIskm0tx87ABQ1h_fqKilnIY.svg';
            $language_en->status = 1;

            if($language_en->save())
            {
                echo "      > Language EN has been created successfully.\n";
            }
            else
            {
                echo "      > m190203_224324_init_values_table_language cannot create datas for language EN.\n";
                return false;
            }
        }


    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "      > All languages will be delete when drop table.\n";
        return true;
    }
}
