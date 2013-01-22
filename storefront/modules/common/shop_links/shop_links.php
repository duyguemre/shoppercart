<?php   
class shop_links extends module {
	public function process() {	 		
		
		$this->data['home'] = $this->url->link('home','', 'NOSSL');
		$this->data['text_home'] = "Home";
		$this->data['wishlist'] = $this->url->link('account','account_management=wishlist', 'NOSSL');		
		$this->data['text_wishlist'] = "Wishlist";
		$this->data['account'] = $this->url->link('account','', 'NOSSL');
		$this->data['text_account'] = "Account";		
		$this->data['shopping_cart'] = $this->url->link('shoppingcart','', 'NOSSL');
		$this->data['text_shopping_cart'] = "Shopping Cart";		
		$this->data['checkout'] = $this->url->link('checkout','', 'NOSSL');
		$this->data['text_checkout'] = "Checkout";
	} 	
}
?>