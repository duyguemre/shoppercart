<?php
class change_password extends module {
	public $back_page;
	
	protected function process() {	
    	$this->data['heading_title'] = $this->text('heading_title','password@account');
    	$this->data['text_password'] = $this->text('text_password','password@account');
    	$this->data['entry_password'] = $this->text('entry_password','password@account');
    	$this->data['entry_confirm'] = $this->text('entry_confirm','password@account');
    	$this->data['button_continue'] = $this->text('button_continue','common@basic');
    	$this->data['button_back'] = $this->text('button_back','common@basic');    	
		if(isset($this->error['password'])) $this->data['error_password'] = $this->error['password'];
		else $this->data['error_password'] = '';
		if(isset($this->error['confirm'])) $this->data['error_confirm'] = $this->error['confirm'];	
		else $this->data['error_confirm'] = '';
		$this->data['action'] = $this->url->link($this->page);
		$this->data['password'] = $this->services->get_post_attribute('password');
		$this->data['confirm'] = $this->services->get_post_attribute('confirm');		
    	$this->data['back'] = $this->url->link($this->back_page);
  	}
  	
  	protected function change() {
  		if (($this->services->get_server_attribute('REQUEST_METHOD') == 'POST') && $this->validate()) {
  			$this->services->edit_password($this->services->get_customer_email(), $this->services->get_post_attribute('password'));
  			$this->services->set_session_attribute('success', $this->text('text_success','password@account'));
  		}  		
  		$this->process();
  	}
  
  	private function validate() {
    	if ((utf8_strlen($this->services->get_post_attribute('password')) < 4) || (utf8_strlen($this->services->get_post_attribute('password')) > 20)) {
      		$this->error['password'] =$this->text('error_password');
    	}
    	if ($this->services->get_post_attribute('confirm') != $this->services->get_post_attribute('password')) {
      		$this->error['confirm'] = $this->text('error_confirm');
    	}  
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}
?>
