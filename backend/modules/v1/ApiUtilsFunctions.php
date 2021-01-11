<?php

namespace backend\modules\v1;

use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class ApiUtilsFunctions
{
    const TYPE_SUCCESS = 1;
    const TYPE_ERROR = 2;
    const TYPE_NOTFOUND = 3;
    const TYPE_BADREQUEST = 4;
    const TYPE_FORBIDDEN = 5;
    const TYPE_INDEX_RESPONSE = 6;

    /**
     * @param $type
     * @param $messaje
     * @param $data
     * @return array|string
     */
    public static function getResponseType($type,$message = '',$data = null)
    {
        $response = '';

        if($type === self::TYPE_SUCCESS)
        {
            $response = [
                'name' => 'Success',
                'message' => ($message === '')? 'Query success' : $message,
                'code' => 1,
                'status' => 200,
                'type'=> '',
                'data' => (isset($data) && !empty($data))? [$data]: [],
            ];
        }
        elseif($type === self::TYPE_ERROR)
        {
            $response =  [
                'name' => 'Error',
                'message' => ($message === '')? 'Query success with errors' : $message,
                'code' => 0,
                'status' => 204,
                'type'=> '',
                'data' => (isset($data) && !empty($data))? [$data]: [],
            ];
        }
        elseif($type === self::TYPE_INDEX_RESPONSE)
        {
            $response = [
                'name' => 'Success',
                'message' => 'Query success',
                'code' => 1,
                'status' => 200,
                'type' => '',
                'total_result' => count($data),
                'data' => (isset($data) && !empty($data))? $data: [],
            ];
        }
        elseif($type === self::TYPE_BADREQUEST)
        {
            throw new BadRequestHttpException($message);
        }
        elseif($type === self::TYPE_FORBIDDEN)
        {
            $message = Yii::t('backend','No está autorizado para acceder a este elemento');
            throw new ForbiddenHttpException($message);
        }
        elseif($type === self::TYPE_NOTFOUND)
        {
            $message = Yii::t('backend','El elemento buscado no existe');
            throw new NotFoundHttpException($message);
        }

        return $response;
    }

}