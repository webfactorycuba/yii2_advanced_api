<?php

use yii\db\Migration;
use backend\models\settings\Setting;
use backend\models\settings\SettingLang;

class m190513_021114_create_init_values_setting extends Migration
{
    public function up()
    {
        $setting_exist = Setting::findOne(1);

        if(isset($setting_exist) && !empty($setting_exist))
        {
            echo "      > Setting already created, not inserted data again.\n";
        }
        else
        {
            $seeting_model = new Setting();
            $seeting_model->id = 1;
            $seeting_model->address = 'Dirección';
            $seeting_model->email = 'webfactorycuba@gmail.com';
            $seeting_model->phone= '+53 88888888';
            $seeting_model->mini_header_logo = null;
            $seeting_model->language = 'es';
            $seeting_model->name = 'Advanced - API';
            $seeting_model->seo_keywords = 'Yii2, WebFactory Cuba';
            $seeting_model->description = 'Descripción en español';

            if($seeting_model->save())
            {
                echo "      > Setting ES inserted sussessfully.\n";
            }
            else
            {
                echo "      > Error with Setting ES.\n";
                return false;
            }

            $setting_created = Setting::findOne(1);
            if($setting_created)
            {
                $english = new SettingLang();
                $english->setting_id = 1;
                $english->name = 'Advanced -API';
                $english->seo_keywords = 'Yii2, WebFactory Cuba';
                $english->description = 'Description in english';
                $english->language = 'en';

                if($english->save())
                {
                    echo "      > Setting EN inserted sussessfully.\n";
                }
                else
                {
                    echo "      > Error with Setting EN.\n";
                    return false;
                }
            }
        }

    }

    public function down()
    {
        echo "      > m190513_021114_create_init_values_setting revert when drop table.\n";

    }
}
