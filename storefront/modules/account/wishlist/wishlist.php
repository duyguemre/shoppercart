<?php 
class wishList extends module {
	public $remove_action_link;
	public $back_action_link;
	
	protected function process() {
		

		if (!$this->services->isset_session_attribute('wishlist')) {
			$this->services->set_session_attribute('wishlist',array());
		}
										
		$this->data['heading_title'] = $this->text('heading_title');			
		$this->data['text_empty'] = $this->text('text_empty'); 
		    	
		$this->data['column_image'] = $this->text('column_image');
		$this->data['column_name'] = $this->text('column_name');
		$this->data['column_model'] = $this->text('column_model');
		$this->data['column_stock'] = $this->text('column_stock');
		$this->data['column_price'] = $this->text('column_price');
		$this->data['column_action'] = $this->text('column_action');
		
		$this->data['button_continue'] = $this->text('button_continue','common@basic');
		$this->data['button_cart'] = $this->text('button_cart','common@basic');
		$this->data['button_remove'] = $this->text('button_remove','common@basic');
		
		if (isset($this->session['success'])) {
			$this->data['success'] = $this->session['success'];
			
			$this->services->unset_session_attribute('success');
		} else {
			$this->data['success'] = '';
		}
							
		$this->data['products'] = array();
	
		foreach ($this->session['wishlist'] as $key => $product_id) {
			$product_info = $this->services->get_product($product_id);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->services->resize($product_info['image']);
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->setting->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->text('text_instock');
				}
							
				if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
					//$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$price = false;
				}
				$price=10;
				
				if ((float)$product_info['special']) {
					//$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$special = false;
				}
				$special=8;
																			
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->remove_action_link . '&remove=' . $product_info['product_id']
				);
			} else {
				unset($this->session['wishlist'][$key]);
			}
		}	

		$this->data['continue'] = $this->back_action_link;		
	}
	
	protected function remove() {
				
		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session['wishlist']);				
			if ($key !== false) {
				unset($this->session['wishlist'][$key]);
			}
			$this->session['success'] = $this->text('text_remove');
		}
		$this->process();
	}
	
	protected function add() {
		$json = array();
		if (!isset($this->session['wishlist'])) {
			$this->services->set_session_attribute('wishlist',array());
		}
				
		if ($this->services->isset_post_attribute('product_id')) {
			$product_id = $this->services->get_post_attribute('product_id');
		} else {
			$product_id = 0;
		}
		
		$product_info = $this->services->get_product($product_id);
		
		if ($product_info) {
			if (!in_array($this->services->get_post_attribute('product_id'), $this->session['wishlist'])) {	
				$this->session['wishlist'][] = $this->services->get_post_attribute('product_id');
			}
			 
			if ($this->customer->isLogged()) {			
				$json['success'] = sprintf($this->text('text_success','common@basic'), $this->url->link('product/product', 'product_id=' . $this->services->get_post_attribute('product_id')), $product_info['name'], $this->url->link('account','account_management=wishlist'));				
			} else {
				$json['success'] = sprintf($this->text('text_login','login@account'), $this->url->link('account', 'account_management=login', 'SSL'), $this->url->link('account', 'account_management=register', 'SSL'), $this->url->link('product', 'product_id=' . $this->services->get_post_attribute('product_id')), $product_info['name'], $this->url->link('account','account_management=wishlist'));				
			} 
			
			$json['total'] = sprintf($this->text('text_wishlist'), ($this->services->isset_session_attribute('wishlist') ? count($this->session['wishlist']) : 0));
		}	
		
		$this->data['json_data']=json_encode($json);
	}	
}
?>