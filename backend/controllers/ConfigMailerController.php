<?php

namespace backend\controllers;

use backend\models\settings\Setting;
use backend\models\settings\TestMailer;
use Yii;
use backend\models\settings\ConfigMailer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\GlobalFunctions;


/**
 * ConfigMailerController implements the CRUD actions for ConfigMailer model.
 */
class ConfigMailerController extends Controller
{

    /**
     * Updates an existing ConfigMailer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                return $this->redirect(['update', 'id'=>$model->id]);
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
            }
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the ConfigMailer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConfigMailer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfigMailer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionTest_mailer()
    {
        $model = new TestMailer();

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if($model->sendEmail())
            {
                return $this->redirect(['update', 'id'=>1]);
            }
        }

            return $this->render('test_mailer', [
                'model' => $model,
            ]);

    }
}
