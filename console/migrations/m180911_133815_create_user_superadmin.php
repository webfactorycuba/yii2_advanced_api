<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m180911_133815_create_user_superadmin
 */
class m180911_133815_create_user_superadmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $user = new User();
	    $user->username = 'superadmin';
	    $user->email = 'webfactorycuba@gmail.com';
	    $user->setPassword('superadmin');
	    $user->name = 'Admin';
	    $user->last_name = 'Super Administrator';
	    $user->status = 10;
	    $user->switch_status = 10;
	    $user->generateAuthKey();
	    $user->role = User::ROLE_SUPERADMIN;
	    $user->scenario= User::SCENARIO_CREATE;
	    $allScenarios= $user->scenarios();

	    if($user->save(true,$allScenarios[$user->scenario]))
	    {
		    $user->save();
		    echo "      > Superadmin user has been created successfully.\n";
	    }
	    else
	    {
	    	echo "      > m180911_133815_create_user_superadmin cannot create user Superadmin.\n";
	        return false;
	    }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $user = User::findByUsername('superadmin');

	    if(isset($user) && !empty($user))
	    {
	    	if($user->delete())
			    echo "      > Superadmin user has been deleted.\n";
	    	else
		    {
		    	echo "      > m180911_133815_create_user_superadmin cannot be reverted.\n";
		        return false;
		    }
	    }

    }

}
