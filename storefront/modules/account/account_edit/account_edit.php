<?php
class account_edit extends module {
	public $back_page;
	
	protected function process() {
		
		$this->data['heading_title'] = $this->text('heading_title','edit@account');
		$this->data['text_your_details'] = $this->text('text_your_details','edit@account');
		$this->data['entry_firstname'] = $this->text('entry_firstname','edit@account');
		$this->data['entry_lastname'] = $this->text('entry_lastname','edit@account');
		$this->data['entry_email'] = $this->text('entry_email','edit@account');
		$this->data['entry_telephone'] = $this->text('entry_telephone','edit@account');
		$this->data['entry_fax'] = $this->text('entry_fax','edit@account');
		$this->data['button_continue'] = $this->text('button_continue','common@basic');
		$this->data['button_back'] = $this->text('button_back','common@basic');

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

		$this->data['action'] = $this->url->link($this->page);

		if ($this->services->get_server_attribute('REQUEST_METHOD') != 'POST') {
			$customer_info = $this->services->get_customer();
		}

		if ($this->services->isset_post_attribute('firstname')) {
			$this->data['firstname'] = $this->services->get_post_attribute('firstname');
		} elseif (isset($customer_info)) {
			$this->data['firstname'] = $customer_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if ($this->services->isset_post_attribute('lastname')) {
			$this->data['lastname'] = $this->services->get_post_attribute('lastname');
		} elseif (isset($customer_info)) {
			$this->data['lastname'] = $customer_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if ($this->services->isset_post_attribute('email')) {
			$this->data['email'] = $this->services->get_post_attribute('email');
		} elseif (isset($customer_info)) {
			$this->data['email'] = $customer_info['email'];
		} else {
			$this->data['email'] = '';
		}

		if ($this->services->isset_post_attribute('telephone')) {
			$this->data['telephone'] = $this->services->get_post_attribute('telephone');
		} elseif (isset($customer_info)) {
			$this->data['telephone'] = $customer_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}

		if ($this->services->isset_post_attribute('fax')) {
			$this->data['fax'] = $this->services->get_post_attribute('fax');
		} elseif (isset($customer_info)) {
			$this->data['fax'] = $customer_info['fax'];
		} else {
			$this->data['fax'] = '';
		}

		$this->data['back'] = $this->url->link($this->back_page);

	}
	
	protected function update() {
		
		if (($this->services->get_server_attribute('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->services->edit_customer();				
			$this->services->set_session_attribute('success',$this->text('text_success','edit@account'));
		}
		$this->process();
	}

	private function validate() {
		
		if ((utf8_strlen($this->services->get_post_attribute('firstname')) < 1) || (utf8_strlen($this->services->get_post_attribute('firstname')) > 32)) {
			$this->error['firstname']=$this->text('error_firstname','edit@account');
		}

		if ((utf8_strlen($this->services->get_post_attribute('lastname')) < 1) || (utf8_strlen($this->services->get_post_attribute('lastname')) > 32)) {
			$this->error['lastname']=$this->text('error_lastname','edit@account');
		}

		if ((utf8_strlen($this->services->get_post_attribute('email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->services->get_post_attribute('email'))) {
			$this->error['email']=$this->text('error_email','edit@account');
		}
		
		if (($this->services->get_customer_email() != $this->services->get_post_attribute('email')) && $this->services->get_total_customers_by_email($this->services->get_post_attribute('email'))) {
			$this->error['warning']=$this->text('error_exists','edit@account');
		}

		if ((utf8_strlen($this->services->get_post_attribute('telephone')) < 3) || (utf8_strlen($this->services->get_post_attribute('telephone')) > 32)) {
			$this->error['telephone']=$this->text('error_telephone','edit@account');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>