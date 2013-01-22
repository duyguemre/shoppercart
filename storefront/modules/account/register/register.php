<?php 
class register extends module {	    
	public $login_action_link;
	public $register_action_link;
	public $success_action_link;
  	public function process() {
		$this->document->setTitle($this->text('heading_title'));		
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
    		$this->services->add_customer($this->request->post);

			$this->services->login($this->request->post['email'], $this->request->post['password']);
			
			unset($this->session->data['guest']);
			
			// Default Shipping Address
			if ($this->setting->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];				
			}
			
			// Default Payment Address
			if ($this->setting->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];			
			}
							  	  
	  		$this->services->redirect($this->success_action_link);
    	} 
		
    	$this->data['heading_title'] = $this->text('heading_title');		
		$this->data['text_account_already'] = sprintf($this->text('text_account_already'), $this->login_action_link);
		$this->data['text_your_details'] = $this->text('text_your_details');
    	$this->data['text_your_address'] = $this->text('text_your_address');
    	$this->data['text_your_password'] = $this->text('text_your_password');
		$this->data['text_newsletter'] = $this->text('text_newsletter');
		$this->data['text_yes'] = $this->text('text_yes');
		$this->data['text_no'] = $this->text('text_no');
		$this->data['text_select'] = $this->text('text_select');
		$this->data['text_none'] = $this->text('text_none');
						
    	$this->data['entry_firstname'] = $this->text('entry_firstname');
    	$this->data['entry_lastname'] = $this->text('entry_lastname');
    	$this->data['entry_email'] = $this->text('entry_email');
    	$this->data['entry_telephone'] = $this->text('entry_telephone');
    	$this->data['entry_fax'] = $this->text('entry_fax');
		$this->data['entry_company'] = $this->text('entry_company');
		$this->data['entry_customer_group'] = $this->text('entry_customer_group');
		$this->data['entry_company_id'] = $this->text('entry_company_id');
		$this->data['entry_tax_id'] = $this->text('entry_tax_id');
    	$this->data['entry_address_1'] = $this->text('entry_address_1');
    	$this->data['entry_address_2'] = $this->text('entry_address_2');
    	$this->data['entry_postcode'] = $this->text('entry_postcode');
    	$this->data['entry_city'] = $this->text('entry_city');
    	$this->data['entry_country'] = $this->text('entry_country');
    	$this->data['entry_zone'] = $this->text('entry_zone');
		$this->data['entry_newsletter'] = $this->text('entry_newsletter');
    	$this->data['entry_password'] = $this->text('entry_password');
    	$this->data['entry_confirm'] = $this->text('entry_confirm');

		$this->data['button_continue'] = $this->text('button_continue');
    
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}		
	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
  		if (isset($this->error['company_id'])) {
			$this->data['error_company_id'] = $this->error['company_id'];
		} else {
			$this->data['error_company_id'] = '';
		}
		
  		if (isset($this->error['tax_id'])) {
			$this->data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$this->data['error_tax_id'] = '';
		}
								
  		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
    	$this->data['action'] = $this->register_action_link;
		
		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} else {
			$this->data['company'] = '';
		}

		$this->data['customer_groups'] = array();
		
		if (is_array($this->setting->get('config_customer_group_display'))) {
			$customer_groups = $this->services->get_customer_groups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->setting->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->request->post['customer_group_id'])) {
    		$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = $this->setting->get('config_customer_group_id');
		}
		
		// Company ID
		if (isset($this->request->post['company_id'])) {
    		$this->data['company_id'] = $this->request->post['company_id'];
		} else {
			$this->data['company_id'] = '';
		}
		
		// Tax ID
		if (isset($this->request->post['tax_id'])) {
    		$this->data['tax_id'] = $this->request->post['tax_id'];
		} else {
			$this->data['tax_id'] = '';
		}
						
		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {	
      		$this->data['country_id'] = $this->setting->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id']; 	
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
		} else {
      		$this->data['zone_id'] = '';
    	}
		
    	$this->data['countries'] = $this->services->get_countries();
		
		if (isset($this->request->post['password'])) {
    		$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
		if (isset($this->request->post['newsletter'])) {
    		$this->data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$this->data['newsletter'] = '';
		}	

		if ($this->setting->get('config_account_id')) {			
			$information_info = $this->services->get_information($this->setting->get('config_account_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->text('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->setting->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) {
      		$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = false;
		}		
  	}

  	private function validate() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->text('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->text('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->text('error_email');
    	}

    	if ($this->services->get_total_customers_by_email($this->request->post['email'])) {
      		$this->error['warning'] = $this->text('error_exists');
    	}
		
    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->text('error_telephone');
    	}
		
		// Customer Group
		
		if (isset($this->request->post['customer_group_id']) && is_array($this->setting->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->setting->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}

		$customer_group = $this->services->get_customer_group($customer_group_id);
			
		if ($customer_group) {	
			// Company ID
			if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
				$this->error['company_id'] = $this->text('error_company_id');
			}
			
			// Tax ID 
			if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
				$this->error['tax_id'] = $this->text('error_tax_id');
			}						
		}
		
    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->text('error_address_1');
    	}

    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->text('error_city');
    	}

		$country_info = $this->services->get_country($this->request->post['country_id']);
		
		if ($country_info) {
			if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$this->error['postcode'] = $this->text('error_postcode');
			}
			
			// VAT Validation->LOOOOOOOOOOOOOOOOK
			//$this->load->helper('vat');
			
			if ($this->setting->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
				$this->error['tax_id'] = $this->text('error_vat');
			}
		}

    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->text('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->text('error_zone');
    	}

    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->error['password'] = $this->text('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->text('error_confirm');
    	}
		
		if ($this->setting->get('config_account_id')) {
			$information_info = $this->services->get_information($this->setting->get('config_account_id'));
			
			if ($information_info && !isset($this->request->post['agree'])) {
      			$this->error['warning'] = sprintf($this->text('error_agree'), $information_info['title']);
			}
		}
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}	
}
?>