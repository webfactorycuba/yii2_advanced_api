<?php

namespace backend\controllers;

use backend\models\settings\Setting;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
use Yii;
use backend\models\support\ApiDoc;
use backend\models\support\ApiDocSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;
use yii\web\Response;

/**
 * ApiDocController implements the CRUD actions for ApiDoc model.
 */
class ApiDocController extends Controller
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
     * Lists all ApiDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApiDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ApiDoc model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ApiDoc model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApiDoc();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                if($model->save())
                {
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
     * Updates an existing ApiDoc model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    if($model->save())
                    {
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
     * Deletes an existing ApiDoc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $transaction = \Yii::$app->db->beginTransaction();

        try
        {
            if($model->delete())
            {
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
     * Finds the ApiDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ApiDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApiDoc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing ApiDoc models.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @return mixed
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

    public function actionExport($types)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;

        if(isset($types) && !empty($types))
        {
            $arraytypes = explode(',',$types);
        }else{
            $arraytypes = array_keys(ApiDoc::getGenericSelectType());
        }
        $sections = [];
        foreach ($arraytypes as $type) {
            array_push($sections, [
                'title' => ApiDoc::getGenericSelectType($type),
                'docs' => ApiDoc::find()->where(['type'=>$type])->orderBy('name')->all()
            ]);
        }

        $logo = '<img src="'. Setting::getUrlLogoBySettingAndType(1) .'" width="30" height="20">';
        $content = $this->renderPartial('view_export_pdf',['sections'=>$sections]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            'filename' => 'API-DOC.pdf',
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>[$logo.' '.Yii::t('backend','Documentación API').' | | '.Yii::t('backend','Fecha').': '.GlobalFunctions::getCurrentDate('d/m/Y')],
                'SetTitle' => Yii::t('backend','Documentación API'),
                'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                'SetFooter' => ['|'.Yii::t('backend','Página').' {PAGENO}|'],
            ],
            'defaultFont'=>'swiss 721 LT CN BTl.ttf',

        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionSelect_export()
    {
        $model = new ApiDoc();

        if (Yii::$app->request->isAjax)
        {
            if ($model->load(Yii::$app->request->post()))
            {
                $types = '';
                if(isset($model->type_select_export) && !empty($model->type_select_export))
                {
                    $types = implode(',',$model->type_select_export);
                }

                return $this->redirect(['api-doc/export','types' => $types]);
            }
        }
    }

}
