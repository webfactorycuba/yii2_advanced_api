<?php

namespace backend\mail;

use yii\swiftmailer\Mailer;
use backend\models\settings\ConfigMailer;

class CustomMailer extends Mailer
{
    /**
    * @inheritdoc
    */
    public function init()
    {
        /* call parent implementation */
        parent::init();

        $configuracion= ConfigMailer::findOne(1);

        /* see if we need to set our transport */
        $class   = $configuracion->class;
        $hostname   = $configuracion->host;
        $username   = $configuracion->username;
        $password   = $configuracion->password;
        $port       = $configuracion->port;
        $encryption = ($configuracion->host == 'localhost')? '' : $configuracion->encryption;
        $timeout    = ($configuracion->host == 'localhost')? 30 : $configuracion->timeout;

        if ($hostname && $username && $password) {
            $this->setTransport([
                'class'      => $class,
                'host'       => $hostname,
                'username'   => $username,
                'password'   => $password,
                'port'       => $port,
                'encryption' => $encryption,
                'timeout'    => $timeout,
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ]);
        }

    }


}