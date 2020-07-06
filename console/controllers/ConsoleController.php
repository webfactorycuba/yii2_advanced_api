<?php

namespace console\controllers;

use backend\models\support\CronjobLog;
use backend\models\support\CronjobTask;
use console\models\ConsoleHelper;
use Yii;
use yii\console\Controller;


class ConsoleController extends Controller
{
    /**
     * Action for run test action
     */
    public function actionTest()
    {
        $execution_date = date('Y-m-d H:i:s');

        set_time_limit(0);
        try{
            // TODO: Action here
            $message = Yii::t("backend", "Ejecución correcta");
        }catch (\Exception $exception){
            $message = Yii::t("backend", "Ejecución incorrecta: ") . $exception->getMessage();
        }

        ConsoleHelper::printMessage($message);

//        $taskId = CronjobTask::getTaskIdByName(CronjobTask::JOB_CONST_NAME);
//        if($taskId > 0){
//            CronjobLog::registerJob($taskId, $execution_date, $message);
//        }

        echo '     ---> Action executed';
        echo PHP_EOL;

    }
}