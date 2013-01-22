<?php
abstract class Module extends system {
	private $action = 'process';
	protected $data = array ();
	public $theme_dir;
	public $module_path;
	protected $template_filename;
	protected $error = array ();
	protected function pre_constructor() {
	}
	protected function post_constructor() {
	}
	public function __construct($system, $module_path, $action = NULL) {
		$this->initialize ( $system );
		$this->pre_constructor ();
		if ($action != null)
			$this->action = $action;
		if (isset ( $this->request->get [get_class ( $this )] ))
			$this->action = $this->request->get [get_class ( $this )];
		$this->module_path = $module_path;
		$this->theme_dir = MODULES_DIR . $this->module_path . '/' . get_class ( $this ) . '/theme/' . TEMPLATE_NAME;
		$this->post_constructor ();
	}
	private function get_template_file() {
		$this->data ['module_image_path'] = MODULE_WEB_DIR . $this->module_path . '/' . get_class ( $this ) . '/theme/' . TEMPLATE_NAME . "image/";
		if (! file_exists ( $this->theme_dir )) {
			$this->theme_dir = MODULES_DIR . $this->module_path . '/' . get_class ( $this ) . '/theme/' . DEFAULT_TEMPLATE_NAME;
			$this->data ['module_image_path'] = MODULE_WEB_DIR . $this->module_path . '/' . get_class ( $this ) . '/theme/' . DEFAULT_TEMPLATE_NAME . "image/";
		}
		$module_template_file = $this->theme_dir . $this->template_filename . '.tpl';
		if (! file_exists ( $module_template_file )) {
			$this->data ['template_file'] = $module_template_file;
			$module_template_file = DEFAULT_MODULE_TEMPLATE_FILE;
		}
		return $module_template_file;
	}
	public function render($custom_template = NULL) {
		// get class template, if action changes use it, if custom then use it
		$this->template_filename = get_class ( $this );
		$this->{$this->action} ();
		if (! is_null ( $custom_template ))
			$this->template_filename = $custom_template;
		$filename = $this->get_template_file ();
		if (isset ( $this->data ))
			extract ( $this->data );
		ob_start ();
		require ($filename);
		$output = ob_get_contents ();
		ob_end_clean ();
		return $output;
	}
	public function text($key) {
		return $this->language->text ( $key, $this->module_path . '_' . get_class ( $this ) );
	}
}
?>