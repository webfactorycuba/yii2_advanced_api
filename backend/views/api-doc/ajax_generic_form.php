<?php

use kartik\form\ActiveForm;
use kartik\form\ActiveField;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
?>

    <?php

    $classname = StringHelper::basename(get_class($model_form));
    if(isset($id_form) && !empty($id_form))
    {
        $modal_id = $id_modal;
    }
    else
    {
        $modal_id = strtolower($classname).'Modal';
    }

    $aria_labelledby = 'Modal'.$classname;
    $label_close = Yii::t("backend", "Cerrar");
    if(isset($id_form) && !empty($id_form))
    {
        $form_id = $id_form;
    }
    else
    {
        $form_id = strtolower($classname).'-form';
    }
    $button_id = strtolower($classname).'-ajax-submit';
    $btn_name = ($name_btn_submit !== null)? $name_btn_submit : Yii::t('backend','Crear');
    $modal_class_large = ($modal_large !== null)? $modal_large : 'modal-lg';

    ?>

    <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $aria_labelledby ?>">
        <div class="modal-dialog <?= $modal_class_large ?>" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="<?= $label_close ?>"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?= $title_modal ?></h4>
                </div>
                <?php
                    $form_current = ActiveForm::begin([
                        'id' => $form_id,
                        'action' => [$action_form],
                        'options' => ['enctype' => 'multipart/form-data'],
                        'fieldConfig' => [
                            'showHints' => true,
                            'hintType' => ActiveField::HINT_SPECIAL,
                        ],
                    ]);
                ?>
                <div class="modal-body">
                    <?= $this->render($path_to_renderpartial, ['model' => $model_form, 'form' => $form_current]); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"> </i> <?= Yii::t("backend", "Cancelar") ?></button>
                    <button id="<?= $button_id ?>" type="submit" class="btn btn-primary"><i class="fa fa-plus"> </i> <?= $btn_name ?></button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>



