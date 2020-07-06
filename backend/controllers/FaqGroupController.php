<?php

namespace backend\controllers;

use Yii;
use backend\models\support\FaqGroup;
use backend\models\support\FaqGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;

/**
 * FaqGroupController implements the CRUD actions for FaqGroup model.
 */
class FaqGroupController extends Controller
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
     * Lists all FaqGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FaqGroup model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FaqGroup model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FaqGroup(['status'=>FaqGroup::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                return $this->redirect(['index']);
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing FaqGroup model.
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
            if ($model->load(Yii::$app->request->post()))
            {
                if($model->save())
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
     * Deletes an existing FaqGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete())
        {
            GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
        }
        else
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the FaqGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FaqGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FaqGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing FaqGroup models.
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

                if(!$model->delete())
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
