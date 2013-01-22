<?php
class account_login extends module {
	public $register_page;
	public $forgotten_page;	
	public $success_page;

	protected function process() {
		if($this->customer->isLogged()) return $this->already_logon();
		$this->check_token();
		if (($this->services->get_server_attribute ( 'REQUEST_METHOD' ) == 'POST') && $this->validate ()) {
			$this->services->unset_session_attribute ( 'guest' );			
			// Default Shipping Address
			$address_info = $this->services->get_customer_default_address();			
			if ($address_info) {
				if ($this->setting->get ( 'config_tax_customer' ) == 'shipping') {
					$this->session['shipping_country_id'] = $address_info ['country_id'];
					$this->session ['shipping_zone_id'] = $address_info ['zone_id'];
					$this->session ['shipping_postcode'] = $address_info ['postcode'];
				}
				
				if ($this->setting->get ( 'config_tax_customer' ) == 'payment') {
					$this->session ['payment_country_id'] = $address_info ['country_id'];
					$this->session ['payment_zone_id'] = $address_info ['zone_id'];
				}
			} else {
				$this->services->unset_session_attribute ( 'shipping_country_id' );
				$this->services->unset_session_attribute ( 'shipping_zone_id' );
				$this->services->unset_session_attribute ( 'shipping_postcode' );
				$this->services->unset_session_attribute ( 'payment_country_id' );
				$this->services->unset_session_attribute ( 'payment_zone_id' );
			}
			
			
			// Added strpos check to pass McAfee PCI compliance test
			// (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if ($this->services->isset_post_attribute ( 'redirect' ) && (strpos ( $this->services->get_post_attribute ( 'redirect' ), $this->setting->get ( 'config_url' ) ) !== false || strpos ( $this->services->get_post_attribute ( 'redirect' ), $this->setting->get ( 'config_ssl' ) ) !== false)) {
				$this->services->redirect ( str_replace ( '&amp;', '&', $this->services->get_post_attribute ( 'redirect' ) ) );
			} else {				
				$this->services->redirect($this->url->link($this->success_page));
			}
		}
		$this->data ['heading_title'] = $this->text ( 'heading_title' );
		$this->data ['text_new_customer'] = $this->text ( 'text_new_customer' );
		$this->data ['text_register'] = $this->text ( 'text_register' );
		$this->data ['text_register_account'] = $this->text ( 'text_register_account' );
		$this->data ['text_returning_customer'] = $this->text ( 'text_returning_customer' );
		$this->data ['text_i_am_returning_customer'] = $this->text ( 'text_i_am_returning_customer' );
		$this->data ['text_forgotten'] = $this->text ( 'text_forgotten' );
		
		
		$this->data ['entry_email'] = $this->text ( 'entry_email' );
		$this->data ['entry_password'] = $this->text ( 'entry_password' );
		
		$this->data ['button_continue'] = $this->text ( 'button_continue' );
		$this->data ['button_login'] = $this->text ( 'button_login' );
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		$this->data ['action'] = $this->url->link($this->page);
		$this->data ['register'] = $this->url->link($this->register_page);
		$this->data ['forgotten'] = $this->url->link($this->forgotten_page);

		
		// Added strpos check to pass McAfee PCI compliance test
		// (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if ($this->services->isset_post_attribute ( 'redirect' ) && (strpos ( $this->services->get_post_attribute ( 'redirect' ), $this->setting->get ( 'config_url' ) ) !== false || strpos ( $this->services->get_post_attribute ( 'redirect' ), $this->setting->get ( 'config_ssl' ) ) !== false)) {
			$this->data ['redirect'] = $this->services->get_post_attribute ( 'redirect' );
		} elseif (isset ( $this->session ['redirect'] )) {
			$this->data ['redirect'] = $this->session ['redirect'];
			
			$this->services->unset_session_attribute ( 'redirect' );
		} else {
			$this->data ['redirect'] = '';
		}

		
		$this->data ['email'] = $this->services->get_post_attribute ( 'email' );
		$this->data ['password'] = $this->services->get_post_attribute ( 'password' );
	}
	
	protected function already_logon() {
		$this->data ['heading_title'] = $this->text ( 'already_logon_title' );
		$this->data ['firstname'] = $this->customer->getFirstName();
		$this->data ['lastname'] = $this->customer->getLastName();
		$this->data ['message_text'] = $this->text("message_text");
		$this->template_filename="already_logon";
	}
	
	protected function check_token() {
		
		// Login override for admin users
		if (! empty ( $this->request->get ['token'] )) {
			$SHOP->customer->logout ();
			$customer_info = $this->services->get_customer_by_token ( $this->request->get ['token'] );
			if ($customer_info && $this->services->login ( $customer_info ['email'], '', true )) {
				// Default Addresses
				$address_info = $this->services->get_customer_default_address ();
				;
				
				if ($address_info) {
					if ($this->setting->get ( 'config_tax_customer' ) == 'shipping') {
						$this->session ['shipping_country_id'] = $address_info ['country_id'];
						$this->session ['shipping_zone_id'] = $address_info ['zone_id'];
						$this->session ['shipping_postcode'] = $address_info ['postcode'];
					}
					
					if ($this->setting->get ( 'config_tax_customer' ) == 'payment') {
						$this->session ['payment_country_id'] = $address_info ['country_id'];
						$this->session ['payment_zone_id'] = $address_info ['zone_id'];
					}
				} else {
					$this->services->unset_session_attribute ( 'shipping_country_id' );
					$this->services->unset_session_attribute ( 'shipping_zone_id' );
					$this->services->unset_session_attribute ( 'shipping_postcode' );
					$this->services->unset_session_attribute ( 'payment_country_id' );
					$this->services->unset_session_attribute ( 'payment_zone_id' );
				}
				$this->services->redirect ( $this->success_link);
			}
		}
	}
	private function validate() {
		if (! $this->services->login ( $this->services->get_post_attribute ( 'email' ), $this->services->get_post_attribute ( 'password' ) )) {
			$this->error['warning']= $this->text ( 'error_login' );
		}
		
		$customer_info = $this->services->get_customer_by_email($this->services->get_post_attribute('email'));
		
		if ($customer_info && ! $customer_info ['approved']) {
			$this->error ['warning'] = $this->text ( 'error_approved' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>