<?php

namespace backend\controllers;

use common\models\GlobalFunctions;
use mdm\admin\components\ItemController;
use yii\rbac\Item;
use Yii;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RoleController extends ItemController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $view = $action->controller->getView();
        unset($view->params['breadcrumbs']);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function labels()
    {
        return[
            'Item' => 'Role',
            'Items' => 'Roles',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Item::TYPE_ROLE;
    }

    /**
     * Bulk Deletes for existing Role models.
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
                $auth = Yii::$app->authManager;
                $object = $auth->getRole($value);

                if(!$auth->remove($object))
                {
                    $deleteOK=false;
                    $nameErrorDelete= $nameErrorDelete.'['.$value.'] ';
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
