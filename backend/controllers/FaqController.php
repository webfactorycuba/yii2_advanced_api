<?php

namespace backend\controllers;

use Yii;
use backend\models\support\Faq;
use backend\models\support\FaqSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
     * Lists all Faq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faq model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Faq(['status'=>Faq::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $image = $model->uploadImage();

                if($model->save())
                {
                    if($image){
                        $path = $model->getImageFile();
                        $image->saveAs($path);
                    }

                    $transaction->commit();

                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                    return $this->redirect(['index']);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción creando el elemento'));
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Faq model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            $old_image_file = $model->getImageFile();
            $old_image = $model->image;

            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    $image = $model->uploadImage();

                    // revert back if no valid file instance uploaded
                    if ($image === false) {
                        $model->image = $old_image;
                    }

                    if($model->save())
                    {
                        // upload only if valid uploaded file instance found by main logo
                        if ($image !== false) // delete old and overwrite
                        {
                            if(file_exists($old_image_file))
                            {
                                try{
                                    unlink($old_image_file);
                                }catch (\Exception $exception){
                                    Yii::info("Error deleting image on Setting: " . $old_image_file);
                                    Yii::info($exception->getMessage());
                                }
                            }

                            $path = $model->getImageFile();
                            $image->saveAs($path);
                        }

                        $transaction->commit();

                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                        return $this->redirect(['index']);

                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
                    }
                }
                catch (Exception $e)
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción actualizando el elemento'));
                    $transaction->rollBack();
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
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $transaction = \Yii::$app->db->beginTransaction();

        try
        {
            if($model->delete())
            {
                $model->deleteImage();
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
            }

            return $this->redirect(['index']);
        }
        catch (Exception $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
     * Bulk Deletes for existing Faq models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionMultiple_delete()
    {
        if(Yii::$app->request->post('row_id'))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $pk = Yii::$app->request->post('row_id');
                $count_elements = count($pk);

                $deleteOK = true;
                $nameErrorDelete = '';
                $contNameErrorDelete = 0;

                foreach ($pk as $key => $value)
                {
                    $model= $this->findModel($value);

                    if (!$model->delete()) {
                        $deleteOK = false;
                        $nameErrorDelete = $nameErrorDelete . '[' . $model->question . '] ';
                        $contNameErrorDelete++;
                    } else {
                        $model->deleteImage();
                    }

                }

                if($deleteOK)
                {
                    if($count_elements === 1)
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elementos eliminados correctamente'));
                    }

                    $transaction->commit();
                }
                else
                {
                    if($count_elements === 1)
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                    else
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                        elseif($contNameErrorDelete>1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando los elementos').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
                $transaction->rollBack();
            }

            return $this->redirect(['index']);
        }
    }

}
