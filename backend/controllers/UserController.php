<?php

namespace backend\controllers;

use common\components\Notification;
use common\models\GlobalFunctions;
use common\models\LoginForm;
use Yii;
use common\models\LoginForm as Login;
use common\models\PasswordResetRequest;
use common\models\ResetPassword;
use mdm\admin\models\form\Signup;
use common\models\ChangePassword;
use common\models\User;
use common\models\UserSearch;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\mail\BaseMailer;

/**
 * User controller
 */
class UserController extends Controller
{
    private $_oldMailPath;

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
                    'logout' => ['POST'],
                    'activate' => ['POST'],
                    'multiple_delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
		$view = $action->controller->getView();
        unset($view->params['breadcrumbs']);
		
        if (parent::beforeAction($action)) {
            if (Yii::$app->has('mailer') && ($mailer = Yii::$app->getMailer()) instanceof BaseMailer) {
                /* @var $mailer BaseMailer */
                $this->_oldMailPath = $mailer->getViewPath();
                $mailer->setViewPath('@mdm/admin/mail');
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = $this->findModel($id);

	    if($model->username != User::IS_SUPERADMIN)
	    {
		    $avatar= $model->avatar;
		    $fileAvatar = $model->getImageFile();

		    if($model->delete())
		    {
				Yii::$app->authManager->revokeAll($model->id);
				
			    if ($avatar != null || $avatar != '')
			    {
				    if(file_exists($fileAvatar))
				    {
                        try{
                            unlink($fileAvatar);
                        }catch (\Exception $exception){
                            Yii::info("Error deleting image on UserController: " . $fileAvatar);
                            Yii::info($exception->getMessage());
                        }
				    }
			    }

			    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Usuario eliminado satisfactoriamente'));
		    }
		    else
			    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el usuario'));
	    }
	    else
	    {
		    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','No se puede eliminar el usuario superadmin del sistema'));
	    }

	    return $this->redirect(['index']);
    }

    /**
     * Login
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()))
        {
            if($model->login())
            {
                if(!Yii::$app->user->can('backend-access'))
                {
                    Yii::$app->user->logout();
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','El usuario no está autorizado a acceder al backend'));

                    return $this->render('login', [
                        'model'  => $model,
                    ]);
                }

                return $this->goHome();
            }
        }

        return $this->render('login', [
                'model' => $model,
        ]);

    }

    /**
     * Logout
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    /**
     * Signup new user
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Revise su correo electrónico para obtener más instrucciones para restaurar la contraseña'));
                return $this->goHome();
            } else {

                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Ha ocurrido un error. No se ha podido establecer la conexión con el servidor de correo'));
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()))
        {

            if($model->resetPassword())
            {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Nueva contraseña guardada correctamente'));

                return $this->goHome();
            }
            else {

                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando la contraseña'));
            }

        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($model->change())
            {
                GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Contraseña actualizada correctamente'));

                return $this->redirect(['profile']);
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error al actualizar la contraseña'));
            }
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Activate new user
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
	    $user->scenario= User::SCENARIO_UPDATE;
	    $user->switch_status = 0;
	    $user->role = GlobalFunctions::getRol($id);

        if ($user->status == User::STATUS_INACTIVE)
        {
            $user->status = User::STATUS_ACTIVE;

	        $allScenarios= $user->scenarios();

	        if($user->save(true,$allScenarios[$user->scenario]))
	        {
		        $user->save();
		        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Usuario activado satisfactoriamente'));
		        return $this->redirect(['index']);
            }
            else
            {

                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
	            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error activando el usuario'));
	            return $this->redirect(['index']);
            }
        }
        return $this->goHome();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
	public function actionCreate()
	{
		$model = new User();
		$model->scenario= User::SCENARIO_CREATE;
        $model->switch_status = 1;

		if($model->load(Yii::$app->request->post()))
		{
			$image = $model->uploadImage();

			$model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
			$model->auth_key= Yii::$app->security->generateRandomString();
			$model->auth_key_test= Yii::$app->security->generateRandomString();

			if($model->switch_status === '1')
				$model->status = 10;
			else
				$model->status = 0;

			$model_role = $model->role;

			$allScenarios= $model->scenarios();

			if($model->save(true,$allScenarios[$model->scenario]))
			{
				if($model->save())
                {
                    $role = Yii::$app->authManager->getRole($model_role);
                    Yii::$app->authManager->revokeAll($model->id);
                    Yii::$app->authManager->assign($role, $model->id);
                }

				// upload only if valid uploaded file instance found
				if ($image !== false) {
					$path = $model->getImageFile();
					$image->saveAs($path);
				}

                Notification::notify(Notification::KEY_NEW_USER_REGISTRED, 1, $model->id);

				GlobalFunctions::addFlashMessage('success',Yii::t('backend','Usuario creado satisfactoriamente'));

				return $this->redirect(['index']);

			}
			else
			{
				GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el usuario'));

				return $this->render('create', ['model' => $model]);
			}

		}

		return $this->render('create', ['model' => $model]);
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario= User::SCENARIO_UPDATE;
		$model->password_hash= '';

		$oldFile = $model->getImageFile();
		$oldAvatar = $model->avatar;

		if($model->status === 10)
			$model->switch_status = 1;
		else
			$model->switch_status = 0;

		$old_role = GlobalFunctions::getRol($model->id);

		$model->role = $old_role;

		if($model->load(Yii::$app->request->post()))
		{
			if($model->switch_status === '1')
				$model->status = 10;
			else
				$model->status = 0;

			// process uploaded image file instance
			$image = $model->uploadImage();

			// revert back if no valid file instance uploaded
			if ($image === false) {
				$model->avatar = $oldAvatar;
			}

			if(empty($model->password_hash))
				$model->password_hash = $model->getOldAttribute('password_hash');
			else
				$model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);

            $model_role = $model->role;
			$allScenarios= $model->scenarios();

			if($model->save(true,$allScenarios[$model->scenario]))
			{
                if($model->save())
                {
                    if ($model->role !== $old_role)
                    {
                        $role = Yii::$app->authManager->getRole($model_role);
                        Yii::$app->authManager->revokeAll($model->id);
                        Yii::$app->authManager->assign($role, $model->id);

                        Notification::notify(Notification::KEY_ROLE_USER_UPDATED, 1, $model->id);

                    }
                }

				// upload only if valid uploaded file instance found
				if ($image !== false) // delete old and overwrite
				{
					if(file_exists($oldFile))
					{
                        try{
                            unlink($oldFile);
                        }catch (\Exception $exception){
                            Yii::info("Error deleting image on UserController: " . $oldFile);
                            Yii::info($exception->getMessage());
                        }
					}

					$path = $model->getImageFile();
					$image->saveAs($path);
				}

				GlobalFunctions::addFlashMessage('success',Yii::t('backend','Usuario actualizado satisfactoriamente'));
				return $this->redirect(['index']);
			}
			else
			{
				GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el usuario'));
				return $this->render('update', ['model' => $model,]);
			}


		} else {
			return $this->render('update', ['model' => $model,]);
		}
	}


	/**
	 * Updates profile an existing User model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 * @return mixed
	 */
    public function actionProfile()
    {
        $id= Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->scenario= User::SCENARIO_UPDATE;

        $old_role = GlobalFunctions::getRol($model->id);
        $model->role = $old_role;

        $oldFile = $model->getImageFile();
        $oldAvatar = $model->avatar;

        $model->switch_status = 1;

        if($model->load(Yii::$app->request->post()))
        {

            // process uploaded image file instance
            $image = $model->uploadImage();

            // revert back if no valid file instance uploaded
            if ($image === false) {
                $model->avatar = $oldAvatar;
            }

            $allScenarios= $model->scenarios();

            if($model->save(true,$allScenarios[$model->scenario]))
            {
                $model->save();

                // upload only if valid uploaded file instance found
                if ($image !== false) // delete old and overwrite
                {
                    if(file_exists($oldFile))
                    {
                        try{
                            unlink($oldFile);
                        }catch (\Exception $exception){
                            Yii::info("Error deleting image on UserController: " . $oldFile);
                            Yii::info($exception->getMessage());
                        }
                    }

                    $path = $model->getImageFile();
                    $image->saveAs($path);
                }

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Perfil de usuario actualizado satisfactoriamente'));
                return $this->redirect(['profile','model' => $model]);
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el perfil del usuario'));
                return $this->render('profile', ['model' => $model,]);
            }


        } else {
            return $this->render('profile', ['model' => $model,]);
        }
    }

    /**
     * Bulk Deletes for existing User models.
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

                if($model->username !== User::IS_SUPERADMIN)
                {
                    $avatar= $model->avatar;
                    $fileAvatar = $model->getImageFile();

                    if($model->delete())
                    {
						Yii::$app->authManager->revokeAll($model->id);
						
                        if ($avatar != null || $avatar != '')
                        {
                            if(file_exists($fileAvatar))
                            {
                                try{
                                    unlink($fileAvatar);
                                }catch (\Exception $exception){
                                    Yii::info("Error deleting image on UserController: " . $fileAvatar);
                                    Yii::info($exception->getMessage());
                                }
                            }
                        }
                    }
                    else
                    {
                        $deleteOK=false;
                        $nameErrorDelete= $nameErrorDelete.'['.$model->name.'] ';
                        $contNameErrorDelete++;
                    }
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
