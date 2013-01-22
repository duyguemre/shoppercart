<?php 
class account extends module{ 
	public $edit_page;
	public $password_page;
	public $address_page;
	public $wishlist_page;
	public $order_page;
	public $download_page;
	public $return_page;
	public $transaction_page;
	public $newsletter_page;
	
	public function process() {
		if ($this->services->isset_session_attribute('success')) {
    		$this->data['success'] = $this->session['success'];			
			$this->services->unset_session_attribute('success');
		} else {
			$this->data['success'] = '';
		}
		
    	$this->data['heading_title'] = $this->text('heading_title');
    	$this->data['text_my_account'] = $this->text('text_my_account');
		$this->data['text_my_orders'] = $this->text('text_my_orders');
		$this->data['text_my_newsletter'] = $this->text('text_my_newsletter');
    	$this->data['text_edit'] = $this->text('text_edit');
    	$this->data['text_password'] = $this->text('text_password');
    	$this->data['text_address'] = $this->text('text_address');
		$this->data['text_wishlist'] = $this->text('text_wishlist');
    	$this->data['text_order'] = $this->text('text_order');
    	$this->data['text_download'] = $this->text('text_download');
		$this->data['text_reward'] = $this->text('text_reward');
		$this->data['text_return'] = $this->text('text_return');
		$this->data['text_transaction'] = $this->text('text_transaction');
		$this->data['text_newsletter'] = $this->text('text_newsletter');

    	$this->data['edit'] = $this->url->link($this->edit_page,'','SSL');
    	$this->data['password'] = $this->url->link($this->password_page,'','SSL');
		$this->data['address'] = $this->url->link($this->address_page,'','SSL');
		$this->data['wishlist'] = $this->url->link($this->wishlist_page,'','SSL');
    	$this->data['order'] = $this->url->link($this->order_page,'','SSL');
    	$this->data['download'] = $this->url->link($this->download_page,'','SSL');
		$this->data['return'] = $this->url->link($this->return_page,'','SSL');
		$this->data['transaction'] = $this->url->link($this->transaction_page,'','SSL');
		$this->data['newsletter'] = $this->url->link($this->newsletter_page,'','SSL');
		
		if ($this->setting->get('reward_status')) {
			$this->data['reward'] = $this->url->link('account', 'account_management=reward', 'SSL');
		} else {
			$this->data['reward'] = '';
		}
		
  	}
}
?>