<?php

namespace backend\controllers;

use Yii;
use backend\models\settings\Setting;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
	        $old_file_main_logo = $model->getImageFile(1);
            $old_file_header_logo = $model->getImageFile(2);
            $old_file_mini_header_logo = $model->getImageFile(3);

	        $old_main_logo = $model->main_logo;
            $old_header_logo = $model->header_logo;
            $old_mini_header_logo = $model->mini_header_logo;

            if ($model->load(Yii::$app->request->post()))
            {
	            // process uploaded main_logo file instance
	            $main_logo = $model->uploadImage('file_main_logo',1);
	            $header_logo = $model->uploadImage('file_header_logo',2);
	            $mini_header_logo = $model->uploadImage('file_mini_header_logo',3);

	            // revert back if no valid file instance uploaded
	            if ($main_logo === false) {
		            $model->main_logo = $old_main_logo;
	            }

                // revert back if no valid file instance uploaded
                if ($header_logo === false) {
                    $model->header_logo = $old_header_logo;
                }

                // revert back if no valid file instance uploaded
                if ($mini_header_logo === false) {
                    $model->mini_header_logo = $old_mini_header_logo;
                }

                if($model->save())
                {
	                // upload only if valid uploaded file instance found by main logo
	                if ($main_logo !== false) // delete old and overwrite
	                {
		                if(file_exists($old_file_main_logo))
		                {
                            try{
                                unlink($old_file_main_logo);
                            }catch (\Exception $exception){
                                Yii::info("Error deleting image on Setting: " . $old_file_main_logo);
                                Yii::info($exception->getMessage());
                            }
		                }

		                $path = $model->getImageFile(1);
		                $main_logo->saveAs($path);
	                }


                    // upload only if valid uploaded file instance found by header logo
                    if ($header_logo !== false) // delete old and overwrite
                    {
                        if(file_exists($old_file_header_logo))
                        {
                            try{
                                unlink($old_file_header_logo);
                            }catch (\Exception $exception){
                                Yii::info("Error deleting image on Setting: " . $old_file_header_logo);
                                Yii::info($exception->getMessage());
                            }
                        }

                        $path = $model->getImageFile(2);
                        $header_logo->saveAs($path);
                    }


                    // upload only if valid uploaded file instance found by mini header logo
                    if ($mini_header_logo !== false) // delete old and overwrite
                    {
                        if(file_exists($old_file_mini_header_logo))
                        {
                            try{
                                unlink($old_file_mini_header_logo);
                            }catch (\Exception $exception){
                                Yii::info("Error deleting image on Setting: " . $old_file_mini_header_logo);
                                Yii::info($exception->getMessage());
                            }
                        }

                        $path = $model->getImageFile(3);
                        $mini_header_logo->saveAs($path);
                    }

                    GlobalFunctions::setFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                    return $this->redirect(['update','id'=>$id]);
                }
                else
                {
                    GlobalFunctions::setFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
                }
            }
        }
        else
        {
            GlobalFunctions::setFlashMessage('warning',Yii::t('backend','El elemento buscado no existe'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }
}
