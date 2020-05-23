<?php

namespace backend\controllers;

use backend\models\settings\Setting;
use Yii;
use backend\models\i18n\Language;
use backend\models\i18n\LanguageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\base\Exception;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends Controller
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
     * Lists all Language models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Language model.
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
     * Creates a new Language model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Language();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()))
        {
            // process upload video file instance
            $image = $model->instanceImage();

            $transaction = \Yii::$app->db->beginTransaction();
            try
            {
                if($model->save() && $model->upload($image))
                {
                    $create_setting = Setting::createDefaultSettingByLanguage($model->code);

                    if($create_setting)
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando los ajustes para este idioma'));

                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                    $transaction->commit();
                    return $this->redirect(['index']);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
                }
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error creando el elemento'));

                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Language model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            $old_file_image = $model->getImageFile();
            $old_image = $model->image;

            if ($model->load(Yii::$app->request->post()))
            {
                // process upload video file instance
                $image = $model->instanceImage();

                // revert back if no valid file instance uploaded
                if ($image === false) {
                    $model->image = $old_image;
                }

                if($model->save() && $model->upload($image,true,$old_file_image))
                {
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                    return $this->redirect(['index']);
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
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            if($model->delete())
            {
                if (!$model->deleteImage()) {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el fichero asociado a este elemento'));
                }

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
            }
            else
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Language the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing Language models.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @return mixed
    */
    public function actionMultiple_delete()
    {
        if(Yii::$app->request->post('row_id'))
        {
            $pk = Yii::$app->request->post('row_id');
            $count_elements = count($pk);

            $deleteOK = true;
            $nameErrorDelete = '';
            $contNameErrorDelete = 0;


            foreach ($pk as $key => $value)
            {
                $model= $this->findModel($value);

                if($model->delete())
                {
                    $model->deleteImage();
                }
                else
                {
                    $deleteOK=false;
                    $nameErrorDelete= $nameErrorDelete.'['.$model->name.'] ';
                    $contNameErrorDelete++;
                }

            }

            if($deleteOK)
            {
                if($count_elements === 1)
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
                else
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elementos eliminados correctamente'));
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

            return $this->redirect(['index']);
        }

    }

}
