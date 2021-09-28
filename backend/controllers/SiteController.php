<?php
namespace backend\controllers;

use common\models\ConfigServerConstants;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'error','change_lang', "ckeditorupload",'phpinfo', 'resource', 'docs'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['docs'],
                'formats' => [
                    'application/json' => Response::FORMAT_HTML,
                ],
            ],
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['resource'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	public function actionChange_lang($lang,$url)
	{
		\Yii::$app->language = $lang;
		$cookie = new Cookie([
            'name' => ConfigServerConstants::LANGUAGE_COOKIE_KEY_BACKEND,
            'value' => $lang,
            'expire' => time() + 60*60*24*30, // 30 days
        ]);
		\Yii::$app->getResponse()->getCookies()->add($cookie);

        return $this->redirect($url);
	}

    public function actionCkeditorupload()
    {
        $funcNum = $_REQUEST['CKEditorFuncNum'];

        if ($_FILES['upload']) {

            if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
                $message = Yii::t('backend', "Por favor, suba alguna imagen");
            } else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 5 * 1024 * 1024) {
                $message = Yii::t("backend","El tamaño de la imagen no debe exceder los ") . " 5MB";
            } else if (($_FILES['upload']["type"] != "image/jpg")
                AND ($_FILES['upload']["type"] != "image/jpeg")
                AND ($_FILES['upload']["type"] != "image/png")) {
                $message = Yii::t("backend","Ha ocurrido un error subiendo la imagen, por favor intente de nuevo");
            } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {

                $message = Yii::t("backend","Formato de imagen no permitido, debe ser JPG, JPEG o PNG.");
            } else {

                $extension = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);

                //Rename the image here the way you want
                $name = "CKE_" . time() . '.' . $extension;

                // Here is the folder where you will save the images
                $folder = '/uploads/ckeditor_images/';
                $realPath = Yii::$app->getBasePath() . "/web" . $folder;
                if (!file_exists($realPath)) {
                    FileHelper::createDirectory($realPath, 0777);
                }

                $url = Yii::$app->urlManager->getBaseUrl() . $folder . $name;

                move_uploaded_file($_FILES['upload']['tmp_name'], $realPath . $name);
                $message = Yii::t("backend","Imagen subida satisfactoriamente");
                //Giving permission to read and modify uploaded image
                chmod($realPath . $name, 0777);
            }

            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'
                . $funcNum . '", "' . $url . '", "' . $message . '" );</script>';

        }
    }

    public function actionPhpinfo()
    {
        return $this->render('phpinfo');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionDocs()
    {
        $this->layout = 'api-doc.php';
        return $this->render('docs');
    }

    public function actionResource()
    {
        $swagger = \Swagger\scan(['../config', '../routes', '../models', '../../common/models/', '../modules']);
        return $swagger;
    }
}
