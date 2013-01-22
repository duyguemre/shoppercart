<?php

class test_shop extends Controller {
	public function __construct() {
		global $SHOP;
		try {		
			parent::__construct();
			$SHOP = $this;
		
			$SHOP->customer = new Customer();
			$SHOP->currency = new Currency();

			$this->template = TEMPLATE_DIR . TEMPLATE . HEADER_TEMPLATE_FILE;
			echo $this->render();
				
		} catch ( Exception $e ) {
			error_log ( $e->getMessage () . " xddv ", 3, "d:/Nameless/logs/php_scripts.log" );
		}
	}
}
?>