<?php
use yii\widgets\Breadcrumbs;
use common\widgets\Custom_Alert;
use yii\helpers\Inflector;
use yii\helpers\Html;

?>
<div class="content-wrapper">
    <section class="content-header">

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <br>

    <section class="content">
        <?= Custom_Alert::widget()  ?>

        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
	                    <?php if (isset($this->blocks['content-header'])) { ?>
                             <h3 class="box-title"><?= $this->blocks['content-header'] ?></h3>
	                    <?php } else { ?>
                            <h3 class="box-title">
			                    <?php
			                    if ($this->title !== null) {
				                    echo Html::encode($this->title);
			                    } else {
				                    echo Inflector::camel2words(
					                    Inflector::id2camel($this->context->module->id)
				                    );
				                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
			                    } ?>
                            </h3>
	                    <?php } ?>


                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
	                    <?= $content ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


    </section>
</div>


