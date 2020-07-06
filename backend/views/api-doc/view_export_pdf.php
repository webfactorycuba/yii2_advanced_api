<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $sections array */

?>


<table width="100%" cellpading="10">
    <tr>
        <td width="10%" valign="middle">

        </td>
        <td width="90%" align="center" valign="top">
            <h2><?= Yii::t('backend', 'Documentación API') ?></h2>
        </td>
        <td align="right" style="padding-right:5px;" width="10%">

        </td>
    </tr>
    <tr>
        <td align="right" colspan="3">&nbsp;

        </td>
    </tr>
</table>


    <?php
    foreach ($sections as $section){
        if(isset($section['docs']) && !empty($section['docs'])){
        ?>

        <h2><?= $section['title'] ?></h2>
        <?php
        foreach ($section['docs'] AS $value) { ?>
            <div style="border: #989898 dotted 1px; padding: 5px;">
                <table  border="0" cellspacing="5" cellpadding="5" width="100%">
                    <tr>
                        <td width="10%" style="font-weight: bold; text-align:left; padding-right: 8px; margin: 5px;"><?= $value->getAttributeLabel('name') ?>:</td>
                        <td width="90%" style="text-align:left;"><a href="<?= Yii::$app->urlManager->getBaseUrl() . $value->name ?>"><?= $value->name ?></a></td>
                    </tr>
                </table>
                <table border="0" cellspacing="5" cellpadding="5" width="100%" style="text-align: left; align-content: left; align-items: left">
                    <tr>
                        <td width="10" style="font-weight: bold; text-align:left; padding: 8px;">
                            <?= Yii::t("backend", "Métodos") ?>:
                            <?php if($value->isGet()){ ?><span style="display: inline-block;"><?= $value->getAttributeLabel('get') ?>&nbsp;&nbsp;</span><?php } ?>
                            <?php if($value->isPost()){ ?><span style="display: inline-block;"><?= $value->getAttributeLabel('post') ?>&nbsp;&nbsp;</span><?php } ?>
                            <?php if($value->isPut()){ ?><span style="display: inline-block;"><?= $value->getAttributeLabel('put') ?>&nbsp;&nbsp;</span><?php } ?>
                            <?php if($value->isDelete()){ ?><span style="display: inline-block;"><?= $value->getAttributeLabel('delete') ?>&nbsp;&nbsp;</span><?php } ?>
                            <?php if($value->isOptions()){ ?><span style="display: inline-block;"><?= $value->getAttributeLabel('options') ?>&nbsp;&nbsp;</span><?php } ?>
                        </td>
                    </tr>
                </table>
                <table  border="0" cellspacing="5" cellpadding="5" width="100%">
                    <tr>
                        <td width="10%" style="font-weight: bold; text-align:left; padding-right: 8px; margin: 5px;"><?= $value->getAttributeLabel('description') ?>:</td>
                    </tr>
                    <tr>
                        <td width="100%" style="text-align:left; margin: 5px;"><?= HtmlPurifier::process($value->description) ?></td>
                    </tr>
                </table>
            </div>
            <br>
            <?php
        }?>
        <hr>
        <br>
    <?php
        }
    }
    ?>

