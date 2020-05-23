<?php

namespace backend\controllers;

use Yii;
use backend\models\i18n\Message;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'multiple_delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($source_message_id)
    {
        $model = new Message();
        $model->id = $source_message_id;

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));
                return $this->redirect(['source-message/update','id'=>$source_message_id]);

            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
            }
        }
            return $this->render('create', [
                'model' => $model
            ]);
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($id, $language)
    {
        $model = $this->findModel($id, $language);

        if(isset($model) && !empty($model))
        {
            if ($model->load(Yii::$app->request->post()))
            {
                if($model->save())
                {
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                    return $this->redirect(['source-message/update', 'id'=>$id]);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
                }
            }
        }
        else
        {
            GlobalFunctions::addFlashMessage('warning',Yii::t('backend','El elemento buscado no existe'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($id, $language,$return_update = false)
    {

        if($this->findModel($id, $language)->delete())
        {
            GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
        }
        else
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
        }

        if(!$return_update)
        {
            return $this->redirect(['index']);
        }
        else
        {
            return $this->redirect(['source-message/update', 'id'=> $id]);
        }
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $language
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $language)
    {
        if (($model = Message::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

}
