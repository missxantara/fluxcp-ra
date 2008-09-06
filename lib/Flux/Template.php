<?php
/**
 * The template is mostly responsible for the presentation logic of things, but
 * currently it also carries the task of executing the action files, which are
 * responsible for the business logic of the application. Maybe this will
 * change in the future, but I'm not sure yet. As long as the developers are
 * forced to adhere to the separation of business logic and presentation logic
 * then I don't think I'll be motivated enough to change this part.
 *
 * Views are rendered within the scope of the template instance, thus $this can
 * be used to access the template instance's methods, and is also how helpers
 * are currently implemented.
 */
class Flux_Template {
	/**
	 * Default data which gets exposed as globals to the templates, and may be
	 * set with the setDefaultData() method.
	 *
	 * @access private
	 * @var array
	 */
	private $defaultData = array();
	
	/**
	 * Request parameters.
	 *
	 * @access protected
	 * @var Flux_Config
	 */
	protected $params;
	
	/**
	 * Base URI of the entire application.
	 *
	 * @access protected
	 * @var string
	 */
	protected $basePath;
	
	/**
	 * Module path.
	 *
	 * @access protected
	 * @var string
	 */
	protected $modulePath;
	
	/**
	 * Module name.
	 *
	 * @access protected
	 * @var string
	 */
	protected $moduleName;
	
	/**
	 * Theme path. This is the path to the selected theme itself, not the real
	 * theme path which contains several themes.
	 *
	 * @access protected
	 * @var string
	 */
	protected $themePath;
	
	/**
	 * Action name. Actions exist as modulePath/moduleName/actionName.php.
	 *
	 * @access protected
	 * @var string
	 */
	protected $actionName;
	
	/**
	 * Action path, would be the path format documented in $actionName.
	 *
	 * @access protected
	 * @var string
	 */
	protected $actionPath;
	
	/**
	 * View name, this is usually the same as the actionName.
	 *
	 * @access protected
	 * @var string
	 */
	protected $viewName;
	
	/**
	 * View path, follows a similar (or rather, exact) format like actionPath,
	 * except there would be a themePath and viewName instead.
	 *
	 * @access protected
	 * @var string
	 */
	protected $viewPath;
	
	/**
	 * Header name. The header file would exist under the themePath's top level
	 * and the headerName would simply be the file's basename without the .php
	 * extension. This name is usually 'header'.
	 *
	 * @access protected
	 * @var string
	 */	
	protected $headerName;
	
	/**
	 * The actual path to the header file.
	 *
	 * @access protected
	 * @var string
	 */
	protected $headerPath;
	
	/**
	 * The footer name.
	 * Similar to headerName. This name is usually 'footer'.
	 *
	 * @access protected
	 * @var string
	 */
	protected $footerName;
	
	/**
	 * The actual path to the footer file.
	 *
	 * @access protected
	 * @var string
	 */
	protected $footerPath;
	
	/**
	 * Whether or not to use mod_rewrite-powered clean URLs or just plain old
	 * query strings.
	 *
	 * @access protected
	 * @var string
	 */
	protected $useCleanUrls;
	
	/**
	 * URL of the current module/action being viewed.
	 *
	 * @access protected
	 * @var string
	 */
	protected $url;
	
	/**
	 * Module/action for missing action's event.
	 *
	 * @access protected
	 * @var array
	 */
	protected $missingActionModuleAction;
	
	/**
	 * Module/action for missing view's event.
	 *
	 * @access protected
	 * @var array
	 */
	protected $missingViewModuleAction;
	
	/**
	 * Construct new template onbject.
	 *
	 * @param Flux_Config $config
	 * @access public
	 */
	public function __construct(Flux_Config $config)
	{
		$this->params                    = $config->get('params');
		$this->basePath                  = $config->get('basePath');
		$this->modulePath                = $config->get('modulePath');
		$this->moduleName                = $config->get('moduleName');
		$this->themePath                 = $config->get('themePath');
		$this->actionName                = $config->get('actionName');
		$this->viewName                  = $config->get('viewName');
		$this->headerName                = $config->get('headerName');
		$this->footerName                = $config->get('footerName');
		$this->useCleanUrls              = $config->get('useCleanUrls');
		$this->missingActionModuleAction = $config->get('missingActionModuleAction', false);
		$this->missingViewModuleAction   = $config->get('missingViewModuleAction', false);
	}
	
	/**
	 * Any data that gets set here will be available to all templates as global
	 * variables unless they are overridden by variables of the same name set
	 * in the render() method.
	 *
	 * @return array
	 * @access public
	 */
	public function setDefaultData(array &$data)
	{
		$this->defaultData = $data;
		return $data;
	}
	
	/**
	 * Render a template, but before doing so, call the action file and render
	 * the header->view->footer in that order.
	 *
	 * @param arary $dataArr Key=>value pairs of variables to be exposed to the template as globals.
	 * @access public
	 */
	public function render(array $dataArr = array())
	{
		$this->actionPath = sprintf('%s/%s/%s.php', $this->modulePath, $this->moduleName, $this->actionName);
		if (!file_exists($this->actionPath)) {
			$this->moduleName = $this->missingActionModuleAction[0];
			$this->actionName = $this->missingActionModuleAction[1];
			$this->viewName   = $this->missingActionModuleAction[1];
			$this->actionPath = sprintf('%s/%s/%s.php', $this->modulePath, $this->moduleName, $this->actionName);
		}
		
		$this->viewPath = sprintf('%s/%s/%s.php', $this->themePath, $this->moduleName, $this->actionName);
		if (!file_exists($this->viewPath)) {
			$this->moduleName = $this->missingViewModuleAction[0];
			$this->actionName = $this->missingViewModuleAction[1];
			$this->viewName   = $this->missingViewModuleAction[1];
			$this->actionPath = sprintf('%s/%s/%s.php', $this->modulePath, $this->moduleName, $this->actionName);
			$this->viewPath   = sprintf('%s/%s/%s.php', $this->themePath, $this->moduleName, $this->viewName);
		}
		
		$this->headerPath = sprintf('%s/%s.php', $this->themePath, $this->headerName);
		$this->footerPath = sprintf('%s/%s.php', $this->themePath, $this->footerName);
		$this->url        = $this->url($this->moduleName, $this->actionName);
		
		// Tidy up!
		if (Flux::config('OutputCleanHTML')) {
			$dispatcher = Flux_Dispatcher::getInstance();
			$tidyIgnore = false;
			if (($tidyIgnores = Flux::config('TidyIgnore')) instanceOf Flux_Config) {
				foreach ($tidyIgnores->getChildrenConfigs() as $ignore) {
					$ignore = $ignore->toArray();
					if (is_array($ignore) && array_key_exists('module', $ignore)) {
						$module = $ignore['module'];
						$action = array_key_exists('action', $ignore) ? $ignore['action'] : $dispatcher->defaultAction;
						if ($this->moduleName == $module && $this->actionName == $action) {
							$tidyIgnore = true;
						}
					}
				}
			}
			if (!$tidyIgnore) {
				ob_start();
			}
		}
		
		// Merge with default data.
		$data = array_merge($this->defaultData, $dataArr);
		
		// Extract data array and make them appear as though they were global
		// variables from the template.
		extract($data, EXTR_REFS);
		
		$preprocessorPath = sprintf('%s/main/preprocess.php', $this->modulePath);
		if (file_exists($preprocessorPath)) {
			include $preprocessorPath;
		}
		
		include $this->actionPath;
		
		if (file_exists($this->headerPath)) {
			include $this->headerPath;
		}
	
		include $this->viewPath;
	
		if (file_exists($this->footerPath)) {
			include $this->footerPath;
		}
		
		// Really, tidy up!
		if (Flux::config('OutputCleanHTML') && !$tidyIgnore && function_exists('tidy_repair_string')) {
			$content = ob_get_clean();
			$content = tidy_repair_string($content, array('indent' => true, 'wrap' => false, 'output-xhtml' => true), 'utf8');
			echo $content;
		}
	}
	
	/**
	 *
	 * @return array
	 */
	public function getMenuItems()
	{
		$auth          = Flux_Authorization::getInstance();
		$defaultAction = Flux_Dispatcher::getInstance()->defaultAction;
		$menuItems     = Flux::config('MenuItems');
		$allowedItems  = array(); //var_dump($auth->actionAllowed('account', 'login'));
		
		if (!($menuItems instanceOf Flux_Config)) {
			return array();
		}
		
		foreach ($menuItems->toArray() as $menuName => $menuItem) {
			$module = array_key_exists('module', $menuItem) ? $menuItem['module'] : false;
			$action = array_key_exists('action', $menuItem) ? $menuItem['action'] : $defaultAction;
			
			if ($auth->actionAllowed($module, $action)) {
				$allowedItems[] = array('name' => $menuName, 'module' => $module, 'action' => $action);
			}
		}
		
		return $allowedItems;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getServerNames()
	{
		return array_keys(Flux::$loginAthenaGroupRegistry);
	}
	
	/**
	 *
	 * @return bool
	 */
	public function hasManyServers()
	{
		return count(Flux::$loginAthenaGroupRegistry) > 1;
	}
	
	/**
	 * Obtain the absolute web path of the specified user path. Specify the
	 * path as a relative path.
	 *
	 * @param string $path Relative path from basePath.
	 * @access public
	 */
	public function path($path)
	{
		if (is_array($path)) {
			$path = implode('/', $path);
		}
		return "{$this->basePath}/$path";
	}
	
	/**
	 * Similar to the path() method, but uses the $themePath as the path from
	 * which the user-specified path is relative.
	 *
	 * @param string $path Relative path from themePath.
	 * @access public
	 */
	public function themePath($path)
	{
		if (is_array($path)) {
			$path = implode('/', $path);
		}
		return $this->path("{$this->themePath}/$path");
	}
	
	/**
	 * Create a URI based on the setting of $useCleanUrls. This will determine
	 * whether or not we will create a mod_rewrite-based clean URL or just a
	 * regular query string based one.
	 *
	 * @param string $moduleName
	 * @param string $actionName
	 * @access public
	 */
	public function url($moduleName, $actionName = null, $params = array())
	{
		$defaultAction = Flux_Dispatcher::getInstance()->defaultAction;
		
		if ($params instanceOf Flux_Config) {
			$params = $params->toArray();
		}
		
		$queryString = '';
		
		if (count($params)) {
			$queryString .= '?';
			foreach ($params as $param => $value) {
				$queryString .= sprintf('%s=%s&', $param, urlencode($value));
			}
			$queryString = rtrim('&', $queryString);
		}
		
		if ($this->useCleanUrls) {
			if ($actionName && $actionName != $defaultAction) {
				return sprintf('%s/%s/%s/%s', $this->basePath, $moduleName, $actionName, $queryString);
			}
			else {
				return sprintf('%s/%s/%s', $this->basePath, $moduleName, $queryString);
			}
		}
		else {
			if ($actionName && $actionName != $defaultAction) {
				return sprintf('%s/?module=%s&action=%s%s', $this->basePath, $moduleName, $actionName, $queryString);
			}
			else {
				return sprintf('%s/?module=%s%s', $this->basePath, $moduleName, $queryString);
			}
		}
	}
	
	/**
	 *
	 */
	public function formatDollar($number)
	{
		$number = (float)$number;
		$amount = number_format(
			$number,
			Flux::config('MoneyDecimalPlaces'),
			Flux::config('MoneyDecimalSymbol'),
			Flux::config('MoneyThousandsSymbol')
		);
		return $amount;
	}
	
	/**
	 *
	 * @return string
	 */
	public function serverUpDown($bool)
	{
		$class = $bool ? 'up' : 'down';
		return sprintf('<span class="%s">%s</span>', $class, $bool ? 'Up' : 'Down');
	}
	
	/**
	 *
	 */
	public function redirect($location = null)
	{
		if (is_null($location)) {
			$location = $this->basePath;
		}
		
		header("Location: $location");
		exit;
	}
	
	/**
	 *
	 */
	public function entireUrl($withRequest = true)
	{
		$proto    = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
		$hostname = $_SERVER['HTTP_HOST'];
		$request  = $_SERVER['REQUEST_URI'];
		
		if ($withRequest) {
			return $proto.$hostname.$request;
		}
		else {
			return $proto.$hostname.'/';
		}
	}
}
?>