<?php   
class storefront_header extends Module {
	public function process() {	 	
		
		$logo = $this->services->get_module('common','logo');
		$welcome = $this->services->get_module('common','welcome');
		$currency_changer = $this->services->get_module('common','currency_changer');
		$search_box = $this->services->get_module('common','search_box');
		$shop_links = $this->services->get_module('common','shop_links');
				
		
		if ($this->services->isset_server_attribute('HTTPS') && (($this->services->get_server_attribute('HTTPS') == 'on') || ($this->services->get_server_attribute('HTTPS') == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
		$currency_changer->action_link = $this->url->link($this->page, '', $connection);
		
		
		$this->data['logo'] = $logo->render();
		$this->data['welcome'] = $welcome->render();
		$this->data['currency_changer'] = $currency_changer->render();
		$this->data['search_box'] = $search_box->render();
		$this->data['shop_links'] = $shop_links->render();		
	} 	
}
?>