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
	 * Construct new template onbject.
	 *
	 * @param Flux_Config $config
	 * @access public
	 */
	public function __construct(Flux_Config $config)
	{
		$this->params     = $config->get('params');
		$this->basePath   = $config->get('basePath');
		$this->modulePath = $config->get('modulePath');
		$this->moduleName = $config->get('moduleName');
		$this->themePath  = $config->get('themePath');
		$this->actionName = $config->get('actionName');
		$this->viewName   = $config->get('viewName');
		$this->headerName = $config->get('headerName');
		$this->footerName = $config->get('footerName');
		
		$this->actionPath = sprintf('%s/%s/%s.php', $this->modulePath, $this->moduleName, $this->actionName);
		if (!file_exists($this->actionPath)) {
			$this->moduleName = 'errors';
			$this->actionName = 'missing_action';
			$this->viewName   = 'missing_action';
			$this->actionPath = sprintf('%s/%s/%s.php', $this->modulePath, $this->moduleName, $this->actionName);
		}
		
		$this->viewPath = sprintf('%s/%s/%s.php', $this->themePath, $this->moduleName, $this->actionName);
		if (!file_exists($this->viewPath)) {
			$this->moduleName = 'errors';
			$this->actionName = 'missing_view';
			$this->viewName   = 'missing_view';
			$this->viewPath   = sprintf('%s/%s/%s.php', $this->themePath, $this->moduleName, $this->viewName);
		}
		
		$this->headerPath = sprintf('%s/%s.php', $this->themePath, $this->headerName);
		$this->footerPath = sprintf('%s/%s.php', $this->themePath, $this->footerName);
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
	public function render(array &$dataArr = array())
	{
		// Merge with default data.
		$data = array_merge(&$this->defaultData, &$dataArr);
		
		// Extract data array and make them appear as though they were global
		// variables from the template.
		extract($data, EXTR_REFS);
		
		include $this->actionPath;
		
		if (file_exists($this->headerPath)) {
			include $this->headerPath;
		}
		
		include $this->viewPath;
		
		if (file_exists($this->footerPath)) {
			include $this->footerPath;
		}
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
}
?>