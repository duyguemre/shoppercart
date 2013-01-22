<?php
class search_box extends module {
	public $account_page;
	public $wishlist_page;
	public $cart_page;
	public $checkout_page;
	public function process() {
		$this->data ['home'] = $this->url->link ( 'home', '', 'NOSSL' );
		$this->data ['text_home'] = "Home";
		$this->data ['wishlist'] = $this->url->link ( $this->wishlist_page, '', 'NOSSL' );
		$this->data ['text_wishlist'] = "Wishlist";
		$this->data ['account'] = $this->url->link ( $this->account_page, '', 'NOSSL' );
		$this->data ['text_account'] = "Account";
		$this->data ['shopping_cart'] = $this->url->link ( $this->cart_page, '', 'NOSSL' );
		$this->data ['text_shopping_cart'] = "Shopping Cart";
		$this->data ['checkout'] = $this->url->link ( $this->checkout_page, '', 'NOSSL' );
		$this->data ['text_checkout'] = "Checkout";
	}
}
?>