<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * @SWG\Definition(
 *   definition="LoginForm",
 *   type="object",
 *   required={"username", "password"},
 *   @SWG\Property(property="id", type="integer"),
 *   @SWG\Property(property="username", type="string"),
 *   @SWG\Property(property="auth_key", type="string"),
 *   @SWG\Property(property="auth_key_test", type="string"),
 *   @SWG\Property(property="name", type="string"),
 *   @SWG\Property(property="last_name", type="string"),
 *   @SWG\Property(property="email", type="string"),
 *   @SWG\Property(property="status", type="string"),
 *   @SWG\Property(property="avatar", type="string"),
 *   @SWG\Property(property="position", type="string"),
 *   @SWG\Property(property="seniority", type="string"),
 *   @SWG\Property(property="skills", type="string"),
 *   @SWG\Property(property="personal_stuff", type="string"),
 *   @SWG\Property(property="test_access", type="boolean")
 * )
 *
 * @SWG\Definition(
 *   definition="CurrentUser",
 *   type="object",
 *   required={"auth_key"},
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/LoginForm")
 *   }
 * )
 */

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [

			'username' => Yii::t('common','Nombre de usuario'),
			'password' => Yii::t('common','Contraseña'),
			'rememberMe' => Yii::t('common','Recordarme'),
		];
	}

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if (!$user)
            {
                $this->addError($attribute, Yii::t('common','Usuario o contraseña incorrecta'));
            }
            else
            {
                if ($user->status === GlobalFunctions::STATUS_INACTIVE)
                {
                    $this->addError('username', Yii::t('common','Usuario inactivo'));
                }

                if (!$user->validatePassword($this->password))
                {
                    $this->addError($attribute, Yii::t('common','Contraseña incorrecta'));
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
