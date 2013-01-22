<?php 
class logo extends module{ 
	public function process() {
		
		$this->data['logo'] = "image/data/logo.png";
		$this->data['name'] = "Shopper Cart";
		$this->data['home'] = "http://localhost:81/shoppercart";
		
  	}
}
?>