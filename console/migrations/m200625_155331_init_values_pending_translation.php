<?php

use backend\models\i18n\Message;
use backend\models\i18n\SourceMessage;
use yii\db\Migration;

/**
 * Class m200625_155331_init_values_pending_translation
 */
class m200625_155331_init_values_pending_translation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $source_messages = [
            [255,	'backend', 'Documentación', 'Documentation'],
            [256,	'backend', 'Documentación API', 'API Documentation'],
            [257,	'backend',	'SI', 'YES'],
            [258,	'backend',	'Si', 'Yes'],
            [259,	'backend',	'Ha ocurrido un error cambiando la contraseña.', 'An error occurred changing the password'],
            [260,	'backend',	'Error guardando la traducción en idioma ', 'Error saving the language translation '],
            [261,	'backend',	'Entre la fecha', 'Enter date'],
            [262,	'backend',	'Último', 'Last'],
            [263,	'backend',	'Primero', 'First'],
            [264,	'backend',	'Usuario encontrado', 'User found'],
            [265,	'common',	'segundo', "second"],
            [266,	'common',	'minuto', "minute"],
            [267,	'common',	'hora', "hour"],
            [268,	'common',	'día', "day"],
            [269,	'common',	'mes', "month"],
            [270,	'common',	'Mes', "Month"],
            [271,	'common',	'Meses', "Months"],
            [272,	'common',	'Enero', "January"],
            [273,	'common',	'Febrero', "February"],
            [274,	'common',	'Marzo', "March"],
            [275,	'common',	'Abril', "April"],
            [276,	'common',	'Mayo', "May"],
            [277,	'common',	'Junio', "June"],
            [278,	'common',	'Julio', "July"],
            [279,	'common',	'Agosto', "August"],
            [280,	'common',	'Septiembre', "September"],
            [281,	'common',	'Octubre', "October"],
            [282,	'common',	'Noviembre', "November"],
            [283,	'common',	'Diciembre', "December"],
            [284,	'common',	'descarga', "download"],
            [285,	'backend',	'El tamaño de la imagen no debe exceder los ', "The image size should not exceed "],
            [286,	'backend',	'Error, ha ocurrido una excepción actualizando el elemento', "Error, an exception occurred updating the item"],
            [287,	'backend',	'Error, ha ocurrido una excepción creando el elemento', "Error, an exception occurred creating the item"],
            [288,	'common',	'vista', "view"],
            [289,	'common',	'año', "year"],
            [290,	'common',	'Año', "Year"],
            [291, 'backend', 'Tipo', "Type"],
            [292, 'backend', 'Autenticación', "Authentication"],
            [293, 'backend', 'Prueba', "Test"],
            [294, 'backend', 'Grupo de FAQ', 'FAQ Group'],
            [295, 'backend', 'Grupos de FAQ', 'FAQ Groups'],
            [296, 'backend', 'Pregunta', 'Question'],
            [297, 'backend', 'Respuesta', 'Answer'],
            [298, 'backend', 'Groupo', 'Group'],
            [299, 'backend', 'Fecha de ejecución', 'Execution date'],
            [300, 'backend', 'Tarea de CronJob', 'CronJob Task'],
            [301, 'backend', 'Tareas de CronJob', 'CronJob Tasks'],
            [302, 'backend', 'Trazas de CronJob', 'CronJob Logs'],
            [303, 'backend', 'Traza de CronJob', 'CronJob Log'],
            [304, 'backend', 'Error', 'Error'],
            [305, 'backend', 'Error!', 'Error!'],
            [306, 'backend', 'Satisfactoria', 'Successful'],
            [307, 'backend', 'Fallida', 'Failed'],
            [308, 'backend', 'Se produjo un error y no se pudo eliminar el elmento.', 'An error has occurred and item can not be deleted.'],
            [309, 'backend', 'Recuerde que está accediendo a un punto de prueba.', 'Remember you are accessing to a test endpoint.'],
            [310, 'backend', 'Línea de tiempo', 'Timeline'],
            [311, 'backend', 'Satisfactorias', 'Successfully'],
            [312, 'backend', 'Fallidas', 'Failed'],
            [313, 'backend', 'Ha ocurrido un error', 'An error has occurred'],
            [314, 'backend', 'Se ejecutaron', 'Were executed'],
            [315, 'backend', 'Acciones', "Actions"],
            [316, 'backend', 'Fallido', 'Failed'],
            [317, 'backend', 'Soporte', 'Support'],
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
        $models = SourceMessage::find()->where('id >= 255 AND id <= 317')->all();

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
