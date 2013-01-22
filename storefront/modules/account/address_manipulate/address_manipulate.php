

<?php
class address_manipulate extends module {
	public $back_page;
	
	private function get_error($key) {
		if(isset($this->error[$key]))
			return $this->error[$key];
		return '';
	}
	
	protected function process() {		
		$this->data ['heading_title'] = $this->text ( 'heading_title' );
		$this->data ['text_edit_address'] = $this->text ( 'text_edit_address' );
		$this->data ['text_yes'] = $this->text ( 'text_yes' );
		$this->data ['text_no'] = $this->text ( 'text_no' );
		$this->data ['text_select'] = $this->text ( 'text_select' );
		$this->data ['text_none'] = $this->text ( 'text_none' );
		$this->data ['entry_firstname'] = $this->text ( 'entry_firstname' );
		$this->data ['entry_lastname'] = $this->text ( 'entry_lastname' );
		$this->data ['entry_company'] = $this->text ( 'entry_company' );
		$this->data ['entry_company_id'] = $this->text ( 'entry_company_id' );
		$this->data ['entry_tax_id'] = $this->text ( 'entry_tax_id' );
		$this->data ['entry_address_1'] = $this->text ( 'entry_address_1' );
		$this->data ['entry_address_2'] = $this->text ( 'entry_address_2' );
		$this->data ['entry_postcode'] = $this->text ( 'entry_postcode' );
		$this->data ['entry_city'] = $this->text ( 'entry_city' );
		$this->data ['entry_country'] = $this->text ( 'entry_country' );
		$this->data ['entry_zone'] = $this->text ( 'entry_zone' );
		$this->data ['entry_default'] = $this->text ( 'entry_default' );
		$this->data ['button_continue'] = $this->text ( 'button_continue' );
		$this->data ['button_back'] = $this->text ( 'button_back' );
		$this->data ['error_firstname'] = $this->get_error ( 'firstname' );
		$this->data ['error_lastname'] = $this->get_error ( 'lastname' );
		$this->data ['error_company_id'] = $this->get_error ( 'company_id' );
		$this->data ['error_tax_id'] = $this->get_error ( 'tax_id' );
		$this->data ['error_address_1'] = $this->get_error ( 'address_1' );
		$this->data ['error_city'] = $this->get_error ( 'city' );
		$this->data ['error_postcode'] = $this->get_error ( 'postcode' );
		$this->data ['error_country'] = $this->get_error ( 'country' );
		$this->data ['error_zone'] = $this->get_error ( 'zone' );
		
		if (! $this->services->isset_get_attribute ( 'address_id' )) {
			$this->data ['action'] = $this->url->link($this->page, get_class($this) . '=insert');
		} else {
			$this->data ['action'] = $this->url->link($this->page, get_class($this) . '=update&address_id=' . $this->services->get_get_attribute ('address_id'));
		}
		if ($this->services->isset_get_attribute ( 'address_id' ) && ($this->services->get_server_attribute ( 'REQUEST_METHOD' ) != 'POST')) {
			$address_info = $this->services->get_customer_address ( $this->services->get_get_attribute ( 'address_id' ) );
		}
		
		if ($this->services->isset_post_attribute ( 'firstname' )) {
			$this->data ['firstname'] = $this->services->get_post_attribute ( 'firstname' );
		} elseif (! empty ( $address_info )) {
			$this->data ['firstname'] = $address_info ['firstname'];
		} else {
			$this->data ['firstname'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'lastname' )) {
			$this->data ['lastname'] = $this->services->get_post_attribute ( 'lastname' );
		} elseif (! empty ( $address_info )) {
			$this->data ['lastname'] = $address_info ['lastname'];
		} else {
			$this->data ['lastname'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'company' )) {
			$this->data ['company'] = $this->services->get_post_attribute ( 'company' );
		} elseif (! empty ( $address_info )) {
			$this->data ['company'] = $address_info ['company'];
		} else {
			$this->data ['company'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'company_id' )) {
			$this->data ['company_id'] = $this->services->get_post_attribute ( 'company_id' );
		} elseif (! empty ( $address_info )) {
			$this->data ['company_id'] = $address_info ['company_id'];
		} else {
			$this->data ['company_id'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'tax_id' )) {
			$this->data ['tax_id'] = $this->services->get_post_attribute ( 'tax_id' );
		} elseif (! empty ( $address_info )) {
			$this->data ['tax_id'] = $address_info ['tax_id'];
		} else {
			$this->data ['tax_id'] = '';
		}
		
		$customer_group_info = $this->services->get_customer_group_info ();
		
		if ($customer_group_info) {
			$this->data ['company_id_display'] = $customer_group_info ['company_id_display'];
		} else {
			$this->data ['company_id_display'] = '';
		}
		
		if ($customer_group_info) {
			$this->data ['tax_id_display'] = $customer_group_info ['tax_id_display'];
		} else {
			$this->data ['tax_id_display'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'address_1' )) {
			$this->data ['address_1'] = $this->services->get_post_attribute ( 'address_1' );
		} elseif (! empty ( $address_info )) {
			$this->data ['address_1'] = $address_info ['address_1'];
		} else {
			$this->data ['address_1'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'address_2' )) {
			$this->data ['address_2'] = $this->services->get_post_attribute ( 'address_2' );
		} elseif (! empty ( $address_info )) {
			$this->data ['address_2'] = $address_info ['address_2'];
		} else {
			$this->data ['address_2'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'postcode' )) {
			$this->data ['postcode'] = $this->services->get_post_attribute ( 'postcode' );
		} elseif (! empty ( $address_info )) {
			$this->data ['postcode'] = $address_info ['postcode'];
		} else {
			$this->data ['postcode'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'city' )) {
			$this->data ['city'] = $this->services->get_post_attribute ( 'city' );
		} elseif (! empty ( $address_info )) {
			$this->data ['city'] = $address_info ['city'];
		} else {
			$this->data ['city'] = '';
		}
		
		if ($this->services->isset_post_attribute ( 'country_id' )) {
			$this->data ['country_id'] = $this->services->get_post_attribute ( 'country_id' );
		} elseif (! empty ( $address_info )) {
			$this->data ['country_id'] = $address_info ['country_id'];
		} else {
			$this->data ['country_id'] = $this->setting->get ( 'config_country_id' );
		}
		
		if ($this->services->isset_post_attribute ( 'zone_id' )) {
			$this->data ['zone_id'] = $this->services->get_post_attribute ( 'zone_id' );
		} elseif (! empty ( $address_info )) {
			$this->data ['zone_id'] = $address_info ['zone_id'];
		} else {
			$this->data ['zone_id'] = '';
		}
		
		$this->data ['countries'] = $this->services->get_countries ();
		
		if ($this->services->isset_post_attribute ( 'default' )) {
			$this->data ['default'] = $this->services->get_post_attribute ( 'default' );
		} elseif ($this->services->isset_get_attribute ( 'address_id' )) {
			$this->data ['default'] = $this->services->get_customer_address_id () == $this->services->get_get_attribute ( 'address_id' );
		} else {
			$this->data ['default'] = false;
		}
		
		$this->data ['back'] = $this->url->link($this->back_page);
	}
	protected function insert() {
		if (($this->services->get_server_attribute ( 'REQUEST_METHOD' ) == 'POST') && $this->validateForm ()) {
			$this->services->insert_customer_address ();
			$this->services->set_session_attribute ( 'success',$this->text ( 'text_insert' ) );
			$this->services->redirect ( $this->url->link($this->back_page) );
		}
		$this->process ();
	}
	protected function update() {
		if (($this->services->get_server_attribute ( 'REQUEST_METHOD' ) == 'POST') && $this->validateForm ()) {
			$this->services->update_customer_address ( $this->services->get_get_attribute ( 'address_id' ) );
			
			$this->services->set_session_attribute ( 'success',$this->text ( 'text_update' ) );
			// Default Shipping Address
			if ($this->services->isset_session_attribute ( 'shipping_address_id' ) && ($this->services->get_get_attribute ( 'address_id' ) == $this->services->get_session_attribute ( 'shipping_address_id' ))) {
				$this->services->set_session_attribute ( 'shipping_country_id',$this->services->get_post_attribute ( 'country_id' ) );
				$this->services->set_session_attribute ( 'shipping_zone_id',$this->services->get_post_attribute ( 'zone_id' ) );
				$this->services->set_session_attribute ( 'shipping_postcode',$this->services->get_post_attribute ( 'postcode' ) );
				
				$this->services->unset_session_attribute ( 'shipping_method' );
				$this->services->unset_session_attribute ( 'shipping_methods' );
			}
			
			// Default Payment Address
			if ($this->services->isset_session_attribute ( 'payment_address_id' ) && ($this->services->get_get_attribute ( 'address_id' ) == $this->services->get_session_attribute ( 'payment_address_id' ))) {
				$this->services->set_session_attribute ( 'payment_country_id',$this->services->get_post_attribute ( 'country_id' ) );
				$this->services->set_session_attribute ( 'payment_zone_id',$this->services->get_post_attribute ( 'zone_id' ) );
				
				$this->services->unset_session_attribute ( 'payment_method' );
				$this->services->unset_session_attribute ( 'payment_methods' );
			}
			$this->services->redirect ( $this->url->link($this->back_page) );				
		}
		$this->process ();
	}
	private function validateForm() {
		if ((utf8_strlen ( $this->services->get_post_attribute ( 'firstname' ) ) < 1) || (utf8_strlen ( $this->services->get_post_attribute ( 'firstname' ) ) > 32)) {
			$this->error['firstname']=$this->text ( 'error_firstname' );
		}
		
		if ((utf8_strlen ( $this->services->get_post_attribute ( 'lastname' ) ) < 1) || (utf8_strlen ( $this->services->get_post_attribute ( 'lastname' ) ) > 32)) {
			$this->error['lastname']=$this->text ( 'error_lastname' );
		}
		
		if ((utf8_strlen ( $this->services->get_post_attribute ( 'address_1' ) ) < 3) || (utf8_strlen ( $this->services->get_post_attribute ( 'address_1' ) ) > 128)) {
			$this->error['address_1']=$this->language->get ( 'error_address_1' );
		}
		
		if ((utf8_strlen ( $this->services->get_post_attribute ( 'city' ) ) < 2) || (utf8_strlen ( $this->services->get_post_attribute ( 'city' ) ) > 128)) {
			$this->error['city']=$this->text ( 'error_city' );
		}
		
		$country_info = $this->services->get_country ( $this->services->get_post_attribute ( 'country_id' ) );
		
		if ($country_info) {
			if ($country_info ['postcode_required'] && (utf8_strlen ( $this->services->get_post_attribute ( 'postcode' ) ) < 2) || (utf8_strlen ( $this->services->get_post_attribute ( 'postcode' ) ) > 10)) {
				$this->error['postcode']=$this->text ( 'error_postcode' );
			}
			
			// VAT Validation
			// $this->load->helper('vat');
			
			if ($this->setting->get ( 'config_vat' ) && ! $this->services->empty_post_attribute ( 'tax_id' ) && (vat_validation ( $country_info ['iso_code_2'], $this->services->get_post_attribute ( 'tax_id' ) ) == 'invalid')) {
				$this->error['tax_id']=$this->text ( 'error_vat' );
			}
		}
		
		if ($this->services->get_post_attribute ( 'country_id' ) == '') {
			$this->error['country']=$this->text ( 'error_country' );
		}
		
		if ($this->services->get_post_attribute ( 'zone_id' ) == '') {
			$this->error['zone'] = $this->text('error_zone');
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	protected function delete() {
		if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->services->delete_customer_address($this->services->get_get_attribute('address_id'));
	
			$this->services->set_session_attribute('success',$this->text('text_delete'));
			// Default Shipping Address
			if (isset($this->session['shipping_address_id']) && ($this->request->get['address_id'] == $this->session['shipping_address_id'])) {
				$this->services->unset_session_attribute('shipping_address_id');
				$this->services->unset_session_attribute('shipping_country_id');
				$this->services->unset_session_attribute('shipping_zone_id');
				$this->services->unset_session_attribute('shipping_postcode');
				$this->services->unset_session_attribute('shipping_method');
				$this->services->unset_session_attribute('shipping_methods');
			}
	
			// Default Payment Address
			if (isset($this->session['payment_address_id']) && ($this->request->get['address_id'] == $this->session['payment_address_id'])) {
				$this->services->unset_session_attribute('payment_address_id');
				$this->services->unset_session_attribute('payment_country_id');
				$this->services->unset_session_attribute('payment_zone_id');
				$this->services->unset_session_attribute('payment_method');
				$this->services->unset_session_attribute('payment_methods');
			}
			$this->services->redirect ( $this->url->link($this->back_page) );				
		}
		$this->process();
	}
	 
	private function validateDelete() {
	
		if ($this->services->get_total_addresses($this->customer->getId()) == 1) {
			$this->error['warning']=$this->text('error_delete','address@account');
		}
		 
		if ($this->customer->getAddressId() == $this->request->get['address_id']) {
			$this->error['warning']=$this->text('error_default','address@account');
		}
		 
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	protected function countries() {
		$json = array ();
		$country_info = $this->services->get_country ( $this->services->get_get_attribute ( 'country_id' ) );
		if ($country_info) {
			$json = array (
					'country_id' => $country_info ['country_id'],
					'name' => $country_info ['name'],
					'iso_code_2' => $country_info ['iso_code_2'],
					'iso_code_3' => $country_info ['iso_code_3'],
					'address_format' => $country_info ['address_format'],
					'postcode_required' => $country_info ['postcode_required'],
					'zone' => $this->services->get_zones ( $this->services->get_get_attribute ( 'country_id' ) ),
					'status' => $country_info ['status'] 
			);
		}
		$this->data ['json_data'] = json_encode ( $json );
		$this->response->set_content_type("application/JSON");		
	}
}
?>