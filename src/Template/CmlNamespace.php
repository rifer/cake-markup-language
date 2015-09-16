<?php
/**
 * Base class for namespaces in the Cake Markup Language.
 *
 * PHP 5
 *
 * Cake Markup Language (http://github.com/jameswatts/cake-markup-language)
 * Copyright 2012, James Watts (http://github.com/jameswatts)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, James Watts (http://github.com/jameswatts)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cml.View
 * @since         CakePHP(tm) v 2.1.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace View;

use App\Utility\Set;
use App\View\HelperRegistry;
use Cake\Controller\Controller;
use Cake\View\View;


/**
 * Base class for namespaces in the Cake Markup Language.
 *
 * @package       Cml.View
 */
abstract class CmlNamespace extends Object {

/**
 * Settings for this namespace.
 *
 * @var array
 */
	public $settings = array();

/**
 * Collection of helpers used by the namespace.
 * 
 * @var HelperRegistry
 */
	public $Helpers = null;

/**
 * Reference to the Controller object.
 * 
 * @var Controller
 */
	protected $_Controller = null;

/**
 * Reference to the View object.
 * 
 * @var View
 */
	protected $_View = null;

/**
 * Constructor
 *
 * @param Controller $controller The Controller object.
 * @param View $view The View object.
 * @param array $settings Optional settings to configure the namespace.
 */
	public function __construct(Controller &$controller, View &$view, array $settings = null) {
		$this->_Controller = $controller;
		$this->_View = $view;
		$this->Helpers = new HelperRegistry($view);
		if ($settings) {
			$this->settings = Set::merge($this->settings, $settings);
		}
		if (isset($this->settings['helpers'])) {
			$helpers = HelperRegistry::normalizeObjectArray(Set::normalize((array)$this->settings['helpers']));
			foreach ($helpers as $name => $properties) {
				list($plugin, $class) = pluginSplit($properties['class']);
				$this->$class = $this->Helpers->load($properties['class'], $properties['settings']);
			}
		}
	}

/**
 * Abstract method used to initialize the namespace.
 * 
 * @return void
 */
	abstract public function load();

}

