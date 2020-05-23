<?php
namespace common\widgets;

use Yii;
use yii\bootstrap\Widget;
use kartik\growl\Growl;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Custom_Alert extends Widget
{
	/**
	 * @var array the alert types configuration for the flash messages.
	 * This array is setup as $key => $value, where:
	 * - $key is the name of the session flash variable
	 * - $value is the array:
	 *       - class of alert type (i.e. danger, success, info, warning)
	 *       - icon for alert AdminLTE
	 */

	public $alertTypes = [
		'danger' => [
			'class' => Growl::TYPE_DANGER,
			'icon' => 'icon glyphicon glyphicon-remove-sign',
		],
		'success' => [
			'class' => Growl::TYPE_SUCCESS,
			'icon' => 'icon glyphicon glyphicon-ok-sign',
		],
		'info' => [
			'class' => Growl::TYPE_INFO,
			'icon' => 'icon glyphicon glyphicon-info-sign',
		],
		'warning' => [
			'class' => Growl::TYPE_WARNING,
			'icon' => 'icon glyphicon glyphicon-exclamation-sign',
		],
	];

	/**
	 * @var array the options for rendering the close button tag.
	 */
	public $closeButton = [];


	/**
	 * @var boolean whether to removed flash messages during AJAX requests
	 */
	public $isAjaxRemoveFlash = true;

	/**
	 * Initializes the widget.
	 * This method will register the bootstrap asset bundle. If you override this method,
	 * make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();

		$session = \Yii::$app->getSession();
		$flashes = $session->getAllFlashes();
		$appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

		foreach ($flashes as $type => $data) {
			if (isset($this->alertTypes[$type])) {
				$data = (array) $data;
				foreach ($data as $message) {

					$this->options['class'] = $this->alertTypes[$type]['class'] . $appendCss;
					$this->options['id'] = $this->getId() . '-' . $type;

					echo Growl::widget([
						'type' => $this->alertTypes[$type]['class'],
						'icon' => $this->alertTypes[$type]['icon'],
						'body' => $message,
						'showSeparator' => true,
						'delay' => 0,
						'pluginOptions' => [
							'showProgressbar' => true,
							'placement' => [
								'from' => 'top',
								'align' => 'right',
							]
						]
					]);
				}
				if ($this->isAjaxRemoveFlash && !\Yii::$app->request->isAjax) {
					$session->removeFlash($type);
				}
			}
		}
	}
}
