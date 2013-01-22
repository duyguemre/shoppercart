<?php
class component {
	protected $component_data = array ();
	protected $component_template;
	private $action='process';	
	protected $services;
	protected $utility;		
	private $error=array();
	
	protected function pre_constructor() {		
	}
		
	protected function post_constructor() {		
	}
	
	public function __construct($action=NULL) {
		global $SHOP;
		$this->services = $SHOP->services;
		$this->utility = $SHOP->utility;
		$this->pre_constructor();
		$this->component_template = get_class($this);		
		if($action!=null) $this->action = $action;
		$this->post_constructor();		
	}
	
		
	private function get_component_template_file() {
		$component_template_file = COMPONENT_TEMPLATE_DIR . strtolower ($this->component_template ) . '.tpl';
		$this->component_data['template_file'] = $component_template_file;
		if (! file_exists ( $component_template_file )) { 
			$component_template_file = DEFAULT_MODULE_TEMPLATE_FILE;
		}		
		return $component_template_file;
	}
	
	public function render() {
		$this->{$this->action}();
		$filename = $this->get_component_template_file();
		if(isset($this->component_data)) extract ( $this->component_data );
		ob_start ();
		require ($filename);
		$output = ob_get_contents ();
		ob_end_clean (); 
		return $output;
	}
	
	protected function set_error($key, $value) {
		$this->error[$key] = $value;
	}
	protected function get_error($key) {
		if(isset($this->error[$key])) {
			return $this->error[$key];
		}
		return '';
	}
	protected function has_error() {
		if(count($this->error)>0) return true;
		return false;
	}
}
?>