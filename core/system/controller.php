<?php
abstract class Controller extends system {
	public $debug_info = '';
	public $data = array ();
	protected $modules = array ();
	protected $template;
	protected $layout_code;
	public $models = array ();
	private $loaded_modules = array ();
	public function __construct() {
		parent::__construct ();
		$this->initialize ();
	}
	protected function init() {
		$this->page = "home";
		if (isset ( $this->request->get ['page'] )) {
			$this->page = $this->request->get ['page'];
		}
		if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
			$protocol = HTTPS;
		} else {
			$protocol = HTTP;
		}
	}
	protected function render() {
		$template_file = $this->template;
		if (! file_exists ( $template_file )) {
			$this->add_debug_info ( $template_file );
			$template_file = DEFAULT_TEMPLATE_FILE;
		}
		$this->data ["debug_info"] = $this->debug_info;
		extract ( $this->data );
		ob_start ();
		require ($template_file);
		$output = ob_get_contents ();
		ob_end_clean ();
		return $output;
	}
	protected function load_modules() {
		$page_modules = $this->model ( 'shop_model@base' )->getPageModules ( end ( explode ( '/', $this->page ) ) );
		foreach ( $page_modules as $page_module ) {
			$module = $this->get_module ( $page_module ["module_path"], $page_module ["module_code"] );
			if (get_class ( $module ) == DEFAULT_MODULE_CLASSNAME) {
				$module->set_message ( MODULES_DIR . $page_module ["module_path"] . "/" . $page_module ["module_code"] . "/" . $page_module ["module_code"] . ".php" );
			}
			$this->modules [$page_module ["position_code"]] [$page_module ["row"]] [$page_module ["column"]] ['content'] = $module->render ();
			$this->modules [$page_module ["position_code"]] [$page_module ["row"]] [$page_module ["column"]] ['width'] = $page_module ["width"];
			$this->modules [$page_module ["position_code"]] [$page_module ["row"]] [$page_module ["column"]] ['height'] = $page_module ["height"];
			$this->modules [$page_module ["position_code"]] [$page_module ["row"]] [$page_module ["column"]] ['overflow'] = $page_module ["overflow"];
			$layout_id = $page_module ["layout_id"];
		}
		if (isset ( $layout_id )) {
			$this->layout_code = $this->model ( 'shop_model@base' )->getLayoutCode ( $layout_id );
		} else {
			$this->log_write ( 'Page: ' . $this->page . ' - dont have even a single mpodule! So redirecting to home!' );
			$this->redirect ( $this->get_link ( 'home', '', 'NOSSL' ) );
		}
		$this->generate_module_css_and_js ();
	}
	public function get_module($module_path, $module_code, $action = NULL) {
		$controller = MODULES_DIR . $module_path . "/" . $module_code . "/" . $module_code . ".php";
		if (! file_exists ( $controller )) {
			$controller = MODULES_DIR . DEFAULT_MODULE_PATH . "/" . DEFAULT_MODULE_CLASSNAME . "/" . DEFAULT_MODULE_CLASSNAME . ".php";
			$module_code = DEFAULT_MODULE_CLASSNAME;
			$module_path = 'common/';
		}
		require_once ($controller);
		$module = new $module_code ( $this, $module_path, $action );
		
		$this->loaded_modules [$module_code] = $module;
		$fields = get_object_vars ( $module );
		foreach ( $fields as $key => $value ) {
			$value = $this->setting->get ( $key, 'module_' . get_class ( $module ) );
			if (isset ( $value )) {
				$module->$key = $value;
			}
		}
		$this->language->load_module_language ( $module );
		return $module;
	}
	private function generate_module_css_and_js() {
		$cssoutput = '';
		$jsoutput = '';
		$pattern = '/@module_image_path@/i';
		foreach ( $this->loaded_modules as $module ) {
			$cssfile = $module->theme_dir . get_class ( $module ) . '.css';
			$jsfile = $module->theme_dir . get_class ( $module ) . '.js';
			$replacement = MODULES_WEB_DIR . $module->module_path . '/' . get_class ( $module ) . '/theme/' . TEMPLATE_NAME . 'image/';
			if (file_exists ( $cssfile ))
				$cssoutput .= preg_replace ( $pattern, $replacement, file_get_contents ( $cssfile ) );
			if (file_exists ( $jsfile ))
				$jsoutput .= preg_replace ( $pattern, $replacement, file_get_contents ( $jsfile ) );
		}
		$fh = fopen ( THEME_DIR . 'stylesheet/module_generated.css', 'w' );
		fwrite ( $fh, $cssoutput );
		$fh = fopen ( THEME_DIR . 'javascript/module_generated.js', 'w' );
		fwrite ( $fh, $cssoutput );
	}
	public function model($value) {
		$tokens = explode ( "@", $value );
		$model = $tokens [0];
		$path = $tokens [1];
		$name = str_replace ( '/', '_', $path . '_' . $model );
		if (isset ( $this->models [$name] )) {
			return $this->models [$name];
		}
		$file = MODEL_DIR . $model . '.php';
		if (isset ( $path ) and $path != null)
			$file = MODEL_DIR . $path . '/' . $model . '.php';
		$class = $model;
		if (file_exists ( $file )) {
			include_once ($file);
			$loaded_model = new $class ( $this );
			$this->models [$name] = $loaded_model;
			return $loaded_model;
		} else {
			trigger_error ( 'Error: Could not load model ' . $file . '!' );
			exit ();
		}
	}
	public function add_debug_info($info) {
		$this->log_write ( $info );
		$this->debug_info .= $info;
	}
	public function get_session_attribute($key) {
		return $this->session [$key];
	}
	public function set_session_attribute($key, $value) {
		return $this->session [$key] = $value;
	}
	public function remove_session_attribute($key, $value) {
		return $this->session->data [$key] = null;
	}
	public function log_write($value) {
		$this->debug_info .= $value;
		return $this->log->write ( $value );
	}
}
?>