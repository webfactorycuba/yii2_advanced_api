<?php

use backend\models\i18n\Message;
use backend\models\i18n\SourceMessage;
use yii\db\Migration;

/**
 * Class m200918_140614_init_values_api_request_log_translations
 */
class m200918_140614_init_values_api_request_log_translations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $source_messages = [
            [318, 'backend', 'ID de Acción', "Action ID"],
            [319, 'backend', 'Método', "Method"],
            [320, 'backend', 'Cuerpo', "Body"],
            [321, 'backend', 'Cabeceras', "Headers"],
            [322, 'backend', 'Trazas de Peticiones API', "API Request Logs"],
            [323, 'backend', 'Trazas API', "API Logs"],
            [324, 'backend', 'Agente de Usuario', "User Agent"],
            [325, 'backend', 'Traza', "Log"],
        ];

        echo "   > Inserting translations into SourceMessage and Message table.\n";
        foreach ($source_messages as $key => $value)
        {
            $source = new SourceMessage();
            $source->id= $value[0];
            $source->category = $value[1];
            $source->message = $value[2];
            if(!$source->save())
            {
                echo 'Error en la traducción: '.$source->id;
            }

            $msg = new Message();
            $msg->id = $value[0];
            $msg->language = 'en';
            $msg->translation = $value[3];
            if(!$msg->save())
            {
                echo 'Error en la traducción: '.$source->id;
            }
        }
    }

    public function safeDown()
    {
        $models = SourceMessage::find()->where('id >= 318 AND id <= 325')->all();

        if($models)
        {
            foreach ($models AS $key => $model)
            {
                $model->delete();
            }
        }

        return true;
    }
}
