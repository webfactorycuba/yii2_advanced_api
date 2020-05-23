<?php

namespace backend\controllers;

use backend\models\i18n\Language;
use backend\models\i18n\Message;
use backend\models\i18n\MessageSearch;
use common\models\GlobalFunctions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use backend\models\i18n\SourceMessage;
use backend\models\i18n\SourceMessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\Response;
use yii\base\Exception;
use yii\base\DynamicModel;
use yii\web\UploadedFile;

/**
 * SourceMessageController implements the CRUD actions for SourceMessage model.
 */
class SourceMessageController extends Controller
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
     * Lists all SourceMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SourceMessage model.
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
     * Creates a new SourceMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SourceMessage();

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();
            try
            {
                if($model->save())
                {
                    //get all languages actives except Default Language(ES)
                    $languages_actives = Language::getLanguagesActives('es');

                    foreach ($languages_actives AS $key => $language)
                    {
                        $translation = Message::getTranslationBySourceId($model->id,$language->code);

                        if(!$translation)
                        {
                            $model_i18n = new Message();
                            $model_i18n->id = $model->id;
                            $model_i18n->language = $language->code;
                            $model_i18n->translation = null;

                            if(!$model_i18n->save())
                            {
                                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error guardando la traducción en idioma ').strtoupper($language->code));

                                return $this->render('create', [
                                    'model' => $model,
                                ]);
                            }

                        }
                    }

                    $transaction->commit();
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                    return $this->redirect(['update', 'id' => $model->id]);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
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
     * Updates an existing SourceMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id' => $id])->all();

        if(isset($model) && !empty($model))
        {
	        if ($model->load(Yii::$app->request->post()))
	        {
		        if($model->save())
		        {
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Traducción guardada correctamente'));

                    return $this->redirect(['index']);
		        }
		        else
		        {
			        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error guardando las traducciones'));
		        }

	        }
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Deletes an existing SourceMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete())
	        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando traducción'));
		else
			GlobalFunctions::addFlashMessage('success',Yii::t('backend','Traducción eliminada correctamente'));


	    return $this->redirect(['index']);
    }

    /**
     * Finds the SourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SourceMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    public function actionExport()
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $source_messages = SourceMessage::find()->all();
        $active_languages = Language::getLanguagesActives('es');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'Categoría');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', 'Mensaje original');

        $alphabet = GlobalFunctions::getArrayAlphabet();
        $pivot_alphabet = 4;

        if($active_languages)
        {
            foreach ($active_languages AS $key => $language)
            {
                $upper_language_code = strtoupper($language->code);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($alphabet[$pivot_alphabet].'1', 'Mensaje '.$upper_language_code);
                $pivot_alphabet++;
            }
        }

        $spreadsheet->getActiveSheet()
            ->getStyle('A1:'.$alphabet[$pivot_alphabet-1].'1')
            ->getFont()->setBold(true);

        $pivot_row = 2;
        foreach ($source_messages AS $index => $source_message)
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$pivot_row, $source_message->id);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$pivot_row, $source_message->category);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$pivot_row, $source_message->message);

            $pivot_alphabet = 4;

            if($active_languages)
            {
                foreach ($active_languages AS $key => $language)
                {
                    $message_language = Message::getMessageBySourceId($source_message->id,$language->code);

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($alphabet[$pivot_alphabet].''.$pivot_row, $message_language);
                    $pivot_alphabet++;
                }
            }

            $pivot_row++;

        }

    // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Traducciones');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Traducciones.xlsx"');
        header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }


	public function actionImport()
    {
		$modelImport = new DynamicModel([
			'fileImport'=> Yii::t('backend','Fichero a importar'),
		]);

		$modelImport->addRule(['fileImport'],'required');
		$modelImport->addRule(['fileImport'],'file',['extensions'=>'ods,xls,xlsx'],['maxSize'=>1024*1024]);

		if(Yii::$app->request->post())
		{
			$modelImport->fileImport = UploadedFile::getInstance($modelImport,'fileImport');

			if($modelImport->fileImport && $modelImport->validate())
			{
				$inputFileType = IOFactory::identify($modelImport->fileImport->tempName);
				$objReader = IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$baseRow = 2;

				$alphabet = GlobalFunctions::getArrayAlphabet();

				while(!empty($sheetData[$baseRow][$alphabet[1]]))
				{
					$trans_id = $sheetData[$baseRow][$alphabet[1]];
					$trans_category = $sheetData[$baseRow][$alphabet[2]];
					$trans_message_origin = $sheetData[$baseRow][$alphabet[3]];

					$pivot_alphabet = 4;
                    $active_languages = Language::getLanguagesActives('es');

                    foreach ($active_languages AS $index_language => $active_language)
                    {
                        $message_temp[$active_language->code] = $sheetData[$baseRow][$alphabet[$pivot_alphabet]];
                        $pivot_alphabet++;
                    }

					$model_source_message = SourceMessage::findOne($trans_id);

					if($model_source_message !== null)
					{
						if($model_source_message->message !== $trans_message_origin)
							$model_source_message->message = $trans_message_origin;

                        foreach ($active_languages AS $index => $active_lang)
                        {
                            $model_message_temp = Message::getModelMessage($trans_id,$active_lang->code);

                            if($model_message_temp)
                            {
                                $translation = $message_temp[$active_lang->code];

                                if(isset($translation) && !empty($translation))
                                {
                                    $model_message_temp->translation = $message_temp[$active_lang->code];
                                    $model_message_temp->save();
                                }
                            }
                            else
                            {
                                $translation = $message_temp[$active_lang->code];

                                if(isset($translation) && !empty($translation))
                                {
                                    $create_translation = Message::addMessage($trans_id,$active_lang->code,$message_temp[$active_lang->code]);
                                }
                            }
                        }

						$model_source_message->save();
						$baseRow++;
					}
					else
					{
					    if(isset($trans_message_origin) && !empty($trans_message_origin))
                        {
                            $model_new = new SourceMessage();
                            $model_new->id = $trans_id;
                            $model_new->category = $trans_category;
                            $model_new->message = $trans_message_origin;

                            if($model_new->save())
                            {
                                $pivot = 4;

                                foreach ($active_languages AS $index => $language)
                                {
                                    $trans = $sheetData[$baseRow][$alphabet[$pivot]];
                                    $create_translation = Message::addMessage($model_new->id,$language->code,$trans);
                                    $pivot++;
                                }

                            }
                        }
                        $baseRow++;
					}
				}
				GlobalFunctions::addFlashMessage('success',Yii::t('backend','Fichero importado correctamente'));
				return $this->redirect(['index']);
			}
			else
			{
				GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error importando el fichero'));
			}
		}

		return $this->render('import',[
			'modelImport' => $modelImport,
		]);
	}

    /**
     * Bulk Deletes for existing SourceMessage models.
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
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Traducción eliminada correctamente'));
                else
                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Traducciones eliminadas correctamente'));
            }
            else
            {
                if($count_elements === 1)
                {
                    if($contNameErrorDelete===1)
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando la traducción').': <b>'.$nameErrorDelete.'</b>');
                    }
                }
                else
                {
                    if($contNameErrorDelete===1)
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando la traducción').': <b>'.$nameErrorDelete.'</b>');
                    }
                    elseif($contNameErrorDelete>1)
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando las traducciones').': <b>'.$nameErrorDelete.'</b>');
                    }
                }
            }

            return $this->redirect(['index']);
        }

    }

}
