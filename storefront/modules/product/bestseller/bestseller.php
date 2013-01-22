<?php   
class bestseller extends Module {
	public function process() {	  
		$this->data['logo'] = "image/data/logo.png";
		$this->data['name'] = "Shopper Cart";
		$this->data['home'] = "http://localhost:81/shoppercart";
		
		//$this->data['name'] = $this->controller->get_page();
	} 	
}
?>