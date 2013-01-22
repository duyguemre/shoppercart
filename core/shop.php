<?php

class Shop extends Controller {
	public function process() {
		try {		
			$this->init();				

			//MODULE CALL
			if(isset($this->request->get['module'])) {				
				$tokens = explode('/',$this->request->get['module']);
				$module_code = $tokens[count($tokens)-1];
				//echo str_replace('/' . $tokens, '' , $tokens);
				if(isset($this->request->get['action'])) $action=$this->request->get['action'];
				$module = $this->get_module(str_replace('/' . $module_code, '' , $this->request->get['module']),$module_code, $action);
				$this->response->setOutput($module->render('countries'));				
				$this->response->output();
				exit();
			}
			//////////
			
				
			$this->document->setTitle($this->setting->get("config_title"));
						
			//////LOAD MODULES //////
			$this->load_modules();		
			/////////////////////////
				
			
			////////// SET COMMON PAGE DATA ///////////////////////////
			$this->data ["title"] = $this->document->getTitle();
			$this->data['description'] = $this->document->getDescription();
			$this->data['keywords'] = $this->document->getKeywords();
			$this->data['links'] = $this->document->getLinks();	 
			$this->data['styles'] = $this->document->getStyles();
			$this->data['scripts'] = $this->document->getScripts();			
			if ($this->setting->get('config_icon') && file_exists(IMAGE_DIR . $this->setting->get('config_icon'))) {
				$this->data['icon'] = $this->helper->getImageServer($this->protocol) . $this->setting->get('config_icon');
			} else {
				$this->data['icon'] = '';
			}				
				
			$positions = $this->model('shop_model@base')->getPositions();
			foreach($positions as $position) {
				$this->data [$position["position_code"]] = $this->generate($position["position_code"]);
			}
			////////////////////////////////////////////////////

			$output = "";
			$this->template = TEMPLATE_DIR . TEMPLATE . HEADER_TEMPLATE_FILE;				
			$output = $this->render();
			$this->template = DEFAULT_LAYOUTS_FOLDER .$this->layout_code.  ".tpl";
			$output .= $this->render();
			$this->template = TEMPLATE_DIR . TEMPLATE . FOOTER_TEMPLATE_FILE;
			$output .= $this->render();
			
			$this->response->setOutput($output);
			$this->response->output();		
							 
		} catch ( Exception $e ) {
			error_log ( $e->getMessage () . " xddv ", 3, "d:/Nameless/logs/php_scripts.log" );
		}
	}
	private function generate($position_code) {
		$this->data['modules'] = null;
		if(isset($this->modules[$position_code])) {
			$this->data['modules'] = $this->modules[$position_code];				
		}
		$this->data['section'] = $position_code;				
		$this->template = DEFAULT_POSITION_FOLDER."section.tpl";									
		return $this->render();
	}
}
?>