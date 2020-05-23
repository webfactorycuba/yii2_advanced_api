<?php

use yii\db\Migration;
use backend\models\settings\ConfigMailer;

/**
 * Class m190602_054822_init_values_table_config_mailer
 */
class m190602_054822_init_values_table_config_mailer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        echo "   > Inserting init values into ConfigMailer table.\n";
            $config = new ConfigMailer();
            $config->id = 1;
            $config->class= 'Swift_SmtpTransport';
            $config->host= 'smtp.gmail.com';
            $config->username= 'genericsoftwaresolutionmail@gmail.com';
            $config->password= 'genericsoftwaresolutionmail*2019';
            $config->port= '587';
            $config->encryption= 'tls';
            $config->timeout= '30';

            $config->save();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        ConfigMailer::deleteAll();
    }
}
