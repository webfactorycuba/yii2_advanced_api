<?php
/**
 * Created by PhpStorm.
 * User: Nety
 * Date: 25/10/2020
 * Time: 16:07
 */

namespace backend\modules\v1;


use yii\rest\Serializer;

class CustomSerializer extends Serializer
{
    /**
     * Función para controlar lo que devuelve cada item(view)
     * @param mixed $data
     * @return mixed
     */
    public function serialize($data)
    {
        $data = parent::serialize($data);

        return $data;
    }

    /**
     * Función para controlar lo que devuelve el index
     * @param \yii\data\DataProviderInterface $dataProvider
     * @return array|string
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function serializeDataProvider($dataProvider)
    {
        $data = parent::serializeDataProvider($dataProvider);
        return ApiUtilsFunctions::getResponseType(ApiUtilsFunctions::TYPE_SUCCESS,'',$data);
    }
}