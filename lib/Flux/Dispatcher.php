<?php
require_once 'Flux/Config.php';
require_once 'Flux/Template.php';

/**
 * The dispatcher is used to handle the current request to the application and
 * forward it to the correct module/action, after which point the view should
 * be rendered.
 *
 * Currently all the "important" behavior is done from Flux_Template.
 */
class Flux_Dispatcher {
	/**
	 * Dispatcher instance.
	 *
	 * @access private
	 * @var Flux_Dispatcher
	 */
	private static $dispatcher;
	
	/**
	 * Default module.
	 *
	 * @access public
	 * @var string
	 */
	public $defaultModule;
	
	/**
	 * Default action.
	 *
	 * @access public
	 * @var string
	 */
	public $defaultAction = 'index';
	
	/**
	 * See Flux_Dispatcher::getInstance().
	 *
	 * @access private
	 */
	private function __construct()
	{
		
	}
	
	/**
	 * Construct new dispatcher instance of one doesn't exist. But there can
	 * only be a single instance of the dispatcher.
	 *
	 * @return Flux_Dispatcher
	 * @access public
	 */
	public static function getInstance()
	{
		if (!self::$dispatcher) {
			self::$dispatcher = new Flux_Dispatcher();
		}
		return self::$dispatcher;
	}
	
	/**
	 * Dispatch current request to the correct action and render the view.
	 *
	 * @param array $options Options for the dispatcher.
	 * @access public
	 */
	public function dispatch($options = array())
	{
		$config           = new Flux_Config($options);
		$basePath         = $config->get('basePath');
		$paramsArr        = $config->get('params');
		$modulePath       = $config->get('modulePath');
		$themePath        = $config->get('themePath');
		$defaultModule    = $config->get('defaultModule');
		$defaultAction    = $config->get('defaultAction');
		$missingActionErr = $config->get('missingActionErr');
		$missingViewErr   = $config->get('missingViewErr');
		
		if (!$defaultModule && $this->defaultModule) {
			$defaultModule = $this->defaultModule;
		}
		if (!$defaultAction && $this->defaultAction) {
			$defaultAction = $this->defaultAction;
		}
		
		if (!$defaultModule) {
			throw new Flux_Error('Please set the default module with $dispatcher->setDefaultModule()');
		}
		elseif (!$defaultAction) {
			throw new Flux_Error('Please set the default action with $dispatcher->setDefaultAction()');
		}
		
		if (!$paramsArr) {
			$paramsArr = &$_REQUEST;
		}
		
		// Provide easier access to parameters.
		$params = new Flux_Config($paramsArr);
		
		if (!$params->get('module') && !$params->get('action')) {
			$moduleName = $defaultModule;
			$actionName = $defaultAction;
		}
		elseif ($params->get('module')) {
			$safetyArr  = array('..', '/', '\\');
			$moduleName = str_replace($safetyArr, '', $params->get('module'));
			if ($params->get('action')) {
				$actionName = str_replace($safetyArr, '', $params->get('action'));
			}
			else {
				$actionName = $defaultAction;
			}
		}
		
		$params->set('module', $moduleName);
		$params->set('action', $actionName);
		
		$templateArray  = array(
			'params'     => $params,
			'basePath'   => $basePath,
			'modulePath' => $modulePath,
			'moduleName' => $moduleName,
			'themePath'  => $themePath,
			'actionName' => $actionName,
			'viewName'   => $actionName,
			'headerName' => 'header',
			'footerName' => 'footer'
		);
		$templateConfig = new Flux_Config($templateArray);
		$template       = new Flux_Template($templateConfig);
		
		// Render template! :D
		$template->render();
	}
	
	/**
	 * This usually needs to be called after instanciating the dispatcher, as
	 * it's very necessary to the dispatcher's failsafe functionality.
	 *
	 * @param string $module Module name
	 * @return string
	 * @access public
	 */
	public function setDefaultModule($module)
	{
		$this->defaultModule = $module;
		return $module;
	}
	
	/**
	 * By default, 'index' is the default action for any module, but you may
	 * override that by using this method.
	 *
	 * @param string $action Action name
	 * @return string
	 * @access public
	 */
	public function setDefaultAction($action)
	{
		$this->defaultAction = $action;
		return $action;
	}
}
?>