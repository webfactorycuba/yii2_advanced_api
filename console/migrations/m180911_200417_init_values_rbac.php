<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m180911_200417_init_values_rbac
 */
class m180911_200417_init_values_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $auth = Yii::$app->authManager;

	    //permiso para acceder a todas las acciones del sistema
		    $create_permission = $auth->createPermission('/*');
		    $create_permission->description = 'Todos los permisos' ;
		    $auth->add($create_permission);

        //permiso para cambiar estado de los usuarios
            $create_permission_2 = $auth->createPermission('change-status-users');
            $create_permission_2->description = 'Permiso para poder cambiar el estado de los usuarios' ;
            $auth->add($create_permission_2);

        //permiso para poder acceder al backend
            $create_permission_3 = $auth->createPermission('backend-access');
            $create_permission_3->description = 'Permiso para poder acceder al backend';
            $auth->add($create_permission_3);

		//rol Superadmin para administrar el sistema
		    $superamin_role = $auth->createRole(User::ROLE_SUPERADMIN);
		    $superamin_role->description = 'Rol con todos los permisos';
		    $auth->add($superamin_role);

		//asignacion del permiso raiz creado al rol Superadmin
	        $auth->addChild($superamin_role, $create_permission);
	        $auth->addChild($superamin_role, $create_permission_2);
	        $auth->addChild($superamin_role, $create_permission_3);

	    //asignar el rol al usuario superadmin
		    $superamin_id = User::findByUsername('superadmin')->id;
			$auth->assign($superamin_role, $superamin_id);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $auth = Yii::$app->authManager;

	    $auth->removeAll();

    }

}
