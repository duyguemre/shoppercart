<?php
class Language extends system{
	public $current_language=array();
	private $data=array();
 
	public function __construct($system) {
		$this->initialize($system);
		
		$file = STOREFRONT_DIR . 'language/' . LANGUAGE_NAME . 'common.php';
		if (file_exists ( $file )) {
			$_ = array();
			include_once ($file);
			$this->common_language=$_;
		}				
		$this->set_language ();		
	}
	
	public function __get($key) {
		return $this->data[$key];
	}
	public function __set($key, $value) {
		$this->data[$key] = $value;
	}	
	
	public function load_module_language($module) {		
		$file = MODULES_DIR . $module->module_path . '/' . get_class ( $module ) . '/language/' . LANGUAGE_NAME . get_class($module) . '.php';	
		if (file_exists ( $file )) {
			$_ = array();
			include_once ($file);
			$key = $module->module_path . '_' . get_class($module);
			$this->$key=$_;		
			return $this->$key;				
		} else {
			//trigger_error ( 'Error: Could not load language file for module: ' . $file . '!' );
		}
	}
	
	public function load_language($key, $file) {
		if (file_exists ( $file )) {
			$_ = array();
			include_once ($file);
			$this->$key=$_;
		} else {
			trigger_error ( 'Error: Could not load language file for module: ' . $file . '-' . $key . '!' );
		}
	}
	

	public function text($key, $name=NULL) {		
		if(isset($this->data[$name])) {			
			if(isset($this->data[$name][$key]))
				return $this->data[$name][$key];
		}
		if($name!='common_language') return $this->text($key, 'common_language');					
		return '???' . $key . '=>' . $name . '???';
	}
	

	public function set_language() {
		$languages = array();
		$results = $this->services->get_languages();
	
		foreach ($results as $result) {
			$languages[$result['code']] = $result;
		}
		$detect = '';
	
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) && ($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
			foreach ($browser_languages as $browser_language) {
				foreach ($languages as $key => $value) {
					if ($value['status']) {
						$locale = explode(',', $value['locale']);
						if (in_array($browser_language, $locale)) {
							$detect = $key;
						}
					}
				}
			}
		}
	
		$tmp = $this->session['language'];
		if (isset($tmp_session_language_attribute) && array_key_exists($this->session['language'], $languages) && $languages[$this->session['language']]['status']) {
			$code = $this->session['language'];
		} elseif (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $languages) && $languages[$this->request->cookie['language']]['status']) {
			$code = $this->request->cookie['language'];
		} elseif ($detect) {
			$code = $detect;
		} else {
			$code = $this->setting->get('config_language');
		}
		if (!isset($tmp_session_language_attribute) || $this->session['language'] != $code) {
			$this->session['language']=$code;
		}
	
		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}
		$this->setting->set('config_language_id', $languages[$code]['language_id']);
		$this->setting->set('config_language', $languages[$code]['code']);
	
		$this->language->current_language = $languages[$code];
	}	
	
}
?>