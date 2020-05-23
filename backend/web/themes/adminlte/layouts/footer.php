<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?= date('Y').' '.\backend\models\settings\Setting::getName() ?> .</strong> <?= Yii::t('backend','Todos los derechos reservados') ?>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab"></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div id="control-sidebar-theme-demo-options-tab" class="tab-pane active"><div>
                <h4 class="control-sidebar-heading"><?= Yii::t('backend', 'Opciones de diseño') ?></h4>
                <ul class="list-unstyled clearfix">
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span>
                                <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Azul') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                                <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span>
                                <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Negro') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
                                <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Púrpura') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
                                <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Verde') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
                                <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Rojo') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
                                <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin"><?= Yii::t('backend','Amarillo') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span>
                                <span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Azul-Luz') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix">
                                <span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span>
                                <span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Negro-Luz') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span>
                                <span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Púrpura-Luz') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span>
                                <span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Verde-Luz') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span>
                                <span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Rojo-Luz') ?></p>
                    </li>
                    <li style="float:left; width: 33.33333%; padding: 5px;">
                        <a href="javascript:void(0)" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span>
                                <span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span>
                            </div>
                            <div>
                                <span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span>
                                <span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span>
                            </div>
                        </a>
                        <p class="text-center no-margin" style="font-size: 12px"><?= Yii::t('backend','Amarillo-Luz') ?></p>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.tab-pane -->

    </div>
</aside>




