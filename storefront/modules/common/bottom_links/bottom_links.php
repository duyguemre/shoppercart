<?php   
class bottom_links extends Module {
	
	public function process() {	 				
		$this->data['text_information'] = $this->text("text_information");		
		$this->data['text_service'] = $this->text('text_service');
		$this->data['text_extra'] = $this->text('text_extra');
		$this->data['text_contact'] = $this->text('text_contact');
		$this->data['text_return'] = $this->text('text_return');
		$this->data['text_sitemap'] = $this->text('text_sitemap');
		$this->data['text_manufacturer'] = $this->text('text_manufacturer');
		$this->data['text_voucher'] = $this->text('text_voucher');
		$this->data['text_affiliate'] = $this->text('text_affiliate');
		$this->data['text_special'] = $this->text('text_special');
		$this->data['text_account'] = $this->text('text_account');
		$this->data['text_order'] = $this->text('text_order');
		$this->data['text_wishlist'] = $this->text('text_wishlist');
		$this->data['text_newsletter'] = $this->text('text_newsletter');
		
				
		$this->data['informations'] = array();
		
		foreach ($this->services->get_informations() as $result) {
			if ($result['bottom']) {
				$this->data['informations'][] = array(
						'title' => $result['title'],
						'href'  => $this->url->link('information', 'information_id=' . $result['information_id'])
				);
			}
		}
		
		$this->data['contact'] = $this->url->link('information/contact');
		$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
		$this->data['sitemap'] = $this->url->link('information/sitemap');
		$this->data['manufacturer'] = $this->url->link('product/manufacturer');
		$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['special'] = $this->url->link('product/special');
		$this->data['account'] = $this->url->link('account', '', 'SSL');
		$this->data['order'] = $this->url->link('account/order', '', 'SSL');
		$this->data['wishlist'] = $this->url->link('account', 'account_management=wishlist', 'SSL');
		$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['powered'] = sprintf($this->text('text_powered'), $this->setting->get('config_name'), date('Y', time()));
				
	}
		

}
?>