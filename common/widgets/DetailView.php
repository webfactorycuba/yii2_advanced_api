<?php

/**
 * @package   yii2-detail-view
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2018
 * @version   1.8.1
 */

namespace common\widgets;


use kartik\detail\DetailView as YiiDetailView;

/**
 * DetailView displays the detail of a single data [[model]]. This widget enhances the [[YiiDetailView]] widget with
 * ability to edit detail view data, configure multi columnar layouts, merged section headers, and various other
 * bootstrap styling enhancements.
 *
 * DetailView is best used for displaying a model in a regular format (e.g. each model attribute is displayed as a
 * row in a table or one can define multiple columns by defining child attributes in each attribute configuration.)
 * The model can be either an instance of [[Model]] or an associative array.
 *
 * DetailView uses the [[attributes]] property to determines which model attributes should be displayed and how they
 * should be formatted.
 *
 * A typical usage of DetailView is as follows:
 *
 * ```php
 * echo DetailView::widget([
 *     'model' => $model,
 *     'attributes' => [
 *         'title',               // title attribute (in plain text)
 *         'description:html',    // description attribute in HTML
 *         [                      // the owner name of the model
 *             'label' => 'Owner',
 *             'value' => $model->owner->name,
 *         ],
 *         'created_at:datetime', // creation date formatted as datetime
 *     ],
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class DetailView extends YiiDetailView //implements BootstrapInterface
{

    /**
     * @var array the HTML attributes for the view button. This will toggle the view from edit mode to view mode. The
     *     following special options are recognized:
     * - `label`: the save button label. This will not be HTML encoded.
     *    Defaults to '<span class="glyphicon glyphicon-eye-open"></span>'.
     */
    public $viewOptions = [
        'label'=>'<i class="fa fa-eye"></i>'
    ];

    /**
     * @var array the HTML attributes for the update button. This button will toggle the edit mode on. The following
     *     special options are recognized:
     * - `label`: the update button label. This will not be HTML encoded.
     *    Defaults to '<span class="glyphicon glyphicon-pencil"></span>'.
     */
    public $updateOptions = [
        'label'=>'<i class="fa fa-pencil"></i>'
    ];

    /**
     * @var array the HTML attributes for the reset button. This button will reset the form in edit mode. The following
     *     special options are recognized:
     * - `label`: the reset button label. This will not be HTML encoded.
     *    Defaults to '<span class="glyphicon glyphicon-ban-circle"></span>'.
     */
    public $resetOptions = [
        'label'=>'<i class="fa fa-undo"></i>'
    ];

    /**
     * @var array the HTML attributes for the edit button. The following special options are recognized:
     * - `label`: the delete button label. This will not be HTML encoded. Defaults to
     *   `'<span class="glyphicon glyphicon-trash"></span>'`.
     * - `url`: the delete button url. If not set will default to `#`.
     * - `params`: _array_, the parameters to be passed via ajax which you must set as key value pairs. This will be
     *   automatically json encoded, so you can set JsExpression or callback
     * - `ajaxSettings`: _array_, the ajax settings if you choose to override the delete ajax settings.
     * @see http://api.jquery.com/jquery.ajax/
     * - `confirm': _string_, the confirmation message before triggering delete. Defaults to:
     *   `Yii::t('kvdetail', 'Are you sure you want to delete this item?')`.
     * - `showErrorStack`: _boolean_, whether to show the complete error stack.
     */
    public $deleteOptions = [];

    /**
     * @var array the HTML attributes for the save button. This will default to a form submit button.
     * The following special options are recognized:
     * - `label`: the save button label. This will not be HTML encoded. Defaults to '<span class="glyphicon
     *     glyphicon-floppy-disk"></span>'.
     */
    public $saveOptions = [
        'label'=>'<i class="fa fa-save"></i>'
    ];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->deleteOptions = array_merge($this->deleteOptions,['label'=>'<i class="fa fa-trash"></i>']);
        parent:: init();
    }

    /**
     * @inheritdoc
     * @throws \ReflectionException
     */
    public function run()
    {
        $this->runWidget();
    }

}
