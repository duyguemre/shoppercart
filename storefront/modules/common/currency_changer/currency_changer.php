<?php
class currency_changer extends module {
	public function process() {
		if (isset ( $this->request->post ['currency_code'] )) {
			$this->currency->set ( $this->request->post ['currency_code'] );
			unset ( $this->session ['shipping_method'] );
			unset ( $this->session ['shipping_methods'] );
			$this->services->redirect ( $this->url->link ( $this->page ) );
		}
		$this->data ['text_currency'] = $this->text ( 'text_currency' );
		$this->data ['action'] = $this->url->link ( $this->page );
		$this->data ['currency_code'] = $this->currency->getCode ();
		$this->data ['currencies'] = array ();
		$results = $this->services->get_currencies ();
		foreach ( $results as $result ) {
			if ($result ['status']) {
				$this->data ['currencies'] [] = array (
						'title' => $result ['title'],
						'code' => $result ['code'],
						'symbol_left' => $result ['symbol_left'],
						'symbol_right' => $result ['symbol_right'] 
				);
			}
		}
	}
}
?>