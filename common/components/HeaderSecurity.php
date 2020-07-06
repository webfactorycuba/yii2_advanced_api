<?php

namespace common\components;

use Yii;
use yii\base\Application;

class HeaderSecurity extends \hyperia\security\Headers
{
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        //Remove header conflict
        $app->on(Application::EVENT_BEFORE_REQUEST, function(){
            $header = Yii::$app->response->headers;
            $header->remove('Content-Security-Policy');
            $header->remove('Server');
            $header->set("Server", "Eurolab.co");
            $header->set("Strict-Transport-Security", "max-age=0");

            if(Yii::$app->request->isAjax){
                $header->removeAll();
            }
        });
    }
}