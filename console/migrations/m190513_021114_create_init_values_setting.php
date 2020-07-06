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
            $seeting_model->email = 'dev@coco-leads.com';
            $seeting_model->phone= '+7 977 549 8369';
            $seeting_model->mini_header_logo = null;
            $seeting_model->language = 'es';
            $seeting_model->name = 'Plantilla Avanzada';
            $seeting_model->seo_keywords = 'Yii2, WebFactory Cuba';
            $seeting_model->description = 'Plantilla avanzada para sistemas basados en Yii2.';

            if($seeting_model->save())
            {
                echo "      > Setting ES inserted successfully.\n";
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
                $english->name = 'Advanced Template';
                $english->seo_keywords = 'Yii2, WebFactory Cuba';
                $english->description = 'Advanced template for Yii2 Apps.';
                $english->language = 'en';

                if($english->save())
                {
                    echo "      > Setting EN inserted successfully.\n";
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