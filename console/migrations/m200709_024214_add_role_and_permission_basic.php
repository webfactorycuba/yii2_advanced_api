<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m200709_024214_add_role_and_permission_basic
 */
class m200709_024214_add_role_and_permission_basic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $basic_role = $auth->createRole(User::ROLE_BASIC);
        $basic_role->description = 'Rol con permisos básicos para el acceso al backend y funciones básicas';
        $auth->add($basic_role);

        $backend_permission = $auth->getPermission('backend-access');
        $auth->addChild($basic_role, $backend_permission);

        $create_permission_site = $auth->createPermission('/site/*');
        $create_permission_site->description = '/site/*' ;
        $auth->add($create_permission_site);
        $auth->addChild($basic_role, $create_permission_site);

        $create_permission_notifications = $auth->createPermission('/notifications/*');
        $create_permission_notifications->description = '/notifications/*' ;
        $auth->add($create_permission_notifications);
        $auth->addChild($basic_role, $create_permission_notifications);

        $create_permission_profile = $auth->createPermission('/security/user/profile');
        $create_permission_profile->description = '/security/user/profile' ;
        $auth->add($create_permission_profile);
        $auth->addChild($basic_role, $create_permission_profile);

        $create_permission_requestpassword = $auth->createPermission('/security/user/request-password-reset');
        $create_permission_requestpassword->description = '/security/user/request-password-reset' ;
        $auth->add($create_permission_requestpassword);
        $auth->addChild($basic_role, $create_permission_requestpassword);

        $create_permission_resetpassword = $auth->createPermission('/security/user/reset-password');
        $create_permission_resetpassword->description = '/security/user/reset-password' ;
        $auth->add($create_permission_resetpassword);
        $auth->addChild($basic_role, $create_permission_resetpassword);

        $create_permission_changepassword = $auth->createPermission('/security/user/change-password');
        $create_permission_changepassword->description = '/security/user/change-password' ;
        $auth->add($create_permission_changepassword);
        $auth->addChild($basic_role, $create_permission_changepassword);

        $create_permission_login = $auth->createPermission('/security/user/login');
        $create_permission_login->description = '/security/user/login' ;
        $auth->add($create_permission_login);
        $auth->addChild($basic_role, $create_permission_login);

        $create_permission_logout = $auth->createPermission('/security/user/logout');
        $create_permission_logout->description = '/security/user/logout' ;
        $auth->add($create_permission_logout);
        $auth->addChild($basic_role, $create_permission_logout);

        $create_permission_signup = $auth->createPermission('/security/user/signup');
        $create_permission_signup->description = '/security/user/signup' ;
        $auth->add($create_permission_signup);
        $auth->addChild($basic_role, $create_permission_signup);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $role_basic = $auth->getRole(User::ROLE_BASIC);
        $auth->remove($role_basic);

        $site_permission = $auth->getPermission('/site/*');
        $auth->remove($site_permission);

        $notification_permission = $auth->getPermission('/notifications/*');
        $auth->remove($notification_permission);

        $profile_permission = $auth->getPermission('/security/user/profile');
        $auth->remove($profile_permission);

        $requestpassw_permission = $auth->getPermission('/security/user/request-password-reset');
        $auth->remove($requestpassw_permission);

        $resetpassw_permission = $auth->getPermission('/security/user/reset-password');
        $auth->remove($resetpassw_permission);

        $changepassw_permission = $auth->getPermission('/security/user/change-password');
        $auth->remove($changepassw_permission);

        $login_permission = $auth->getPermission('/security/user/login');
        $auth->remove($login_permission);

        $logout_permission = $auth->getPermission('/security/user/logout');
        $auth->remove($logout_permission);

        $signup_permission = $auth->getPermission('/security/user/signup');
        $auth->remove($signup_permission);
    }
}
