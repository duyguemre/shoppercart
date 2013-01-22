<?php   
class utility{
	private $shop;
	//private $url;
	public function __construct() {
		global $SHOP;
		$this->shop = $SHOP;
		//$this->url = new Url($this->get_setting('config_url'), $this->get_setting('config_use_ssl') ? $this->get_setting('config_ssl') : $this->get_setting('config_url'));		
	}
	
	public function require_component($component_name) {
		require_once (COMPONENT_CONTROLLER_DIR . $component_name . '.php');
	}
	
	public function get_link($page, $args = '', $connection = 'NONSSL') {
		return $this->shop->get_link($page, $args,$connection);
	}
		
	public function language($key, $path) {
		return $this->shop->language($key,$path);		
	}
	
	public function get_post_attribute($key) {
		if(isset($this->shop->request->post[$key])) return $this->shop->request->post[$key];
		return '';
	}

	public function get_get_attribute($key) {
		if(isset($this->shop->request->get[$key])) return $this->shop->request->get[$key];
		return '';
	}
	
	public function get_server_attribute($key) {
		return $this->shop->request->server[$key];
	}
	public function unset_session_attribute($key) {
		$this->shop->session[$key]=null;
	}
	
	public function get_session_attribute($key) {
		return $this->shop->session[$key];
	}
	public function set_session_attribute($key, $value) {
		$this->shop->session[$key] = $value;
	}
	
	public function redirect($link) {
		$this->shop->redirect($link);		
	}
	
	public function isset_get_attribute($key) {
		return isset($this->shop->request->get[$key]);
	}

	public function isset_post_attribute($key) {
		return isset($this->shop->request->post[$key]);
	}

	public function isset_session_attribute($key) {
		return isset($this->shop->session[$key]);
	}

	public function isset_server_attribute($key) {
		return isset($this->shop->server[$key]);
	}

	public function empty_get_attribute($key) {
		return empty($this->shop->request->get[$key]);
	}
	
	public function empty_post_attribute($key) {
		return empty($this->shop->request->post[$key]);
	}
	
	public function empty_session_attribute($key) {
		return empty($this->shop->session[$key]);
	}
	
	public function empty_server_attribute($key) {
		return empty($this->shop->server[$key]);
	}
	
	
	public function get_setting($key) {
		return $this->shop->get_setting($key);
	}	
}
?>