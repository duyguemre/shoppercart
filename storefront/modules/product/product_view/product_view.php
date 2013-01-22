<?php   
class product_view extends Module {
	public function process() {	  
		
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
				
		$product_info = $this->services->get_product($product_id);
		
		if ($product_info) {
			$url = '';
				
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
				
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
				
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
		
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
		
			$this->document->setTitle($product_info['name']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
				
			$this->data['heading_title'] = $product_info['name'];
				
			$this->data['show'] = 1;
			$this->data['text_select'] = $this->text('text_select','common@basic');
			$this->data['text_manufacturer'] = $this->text('text_manufacturer','product@product');
			$this->data['text_model'] = $this->text('text_model','product@product');
			$this->data['text_reward'] = $this->text('text_reward','product@product');
			$this->data['text_points'] = $this->text('text_points','product@product');
			$this->data['text_discount'] = $this->text('text_discount','product@product');
			$this->data['text_stock'] = $this->text('text_stock','product@product');
			$this->data['text_price'] = $this->text('text_price','product@product');
			$this->data['text_tax'] = $this->text('text_tax','product@product');
			$this->data['text_discount'] = $this->text('text_discount','product@product');
			$this->data['text_option'] = $this->text('text_option','product@product');
			$this->data['text_qty'] = $this->text('text_qty','product@product');
			$this->data['text_minimum'] = sprintf($this->text('text_minimum','product@product'), $product_info['minimum']);
			$this->data['text_or'] = $this->text('text_or','product@product');
			$this->data['text_write'] = $this->text('text_write','product@product');
			$this->data['text_note'] = $this->text('text_note','product@product');
			$this->data['text_share'] = $this->text('text_share','product@product');
			$this->data['text_wait'] = $this->text('text_wait','product@product');
			$this->data['text_tags'] = $this->text('text_tags','product@product');
				
			$this->data['entry_name'] = $this->text('entry_name','product@product');
			$this->data['entry_review'] = $this->text('entry_review','product@product');
			$this->data['entry_rating'] = $this->text('entry_rating','product@product');
			$this->data['entry_good'] = $this->text('entry_good','product@product');
			$this->data['entry_bad'] = $this->text('entry_bad','product@product');
			$this->data['entry_captcha'] = $this->text('entry_captcha','product@product');
				
			$this->data['button_cart'] = $this->text('button_cart','common@basic');
			$this->data['button_wishlist'] = $this->text('button_wishlist','common@basic');
			$this->data['button_compare'] = $this->text('button_compare','common@basic');
			$this->data['button_upload'] = $this->text('button_upload','common@basic');
			$this->data['button_continue'] = $this->text('button_continue','common@basic');
				
			$this->data['tab_description'] = $this->text('tab_description','product@product');
			$this->data['tab_attribute'] = $this->text('tab_attribute','product@product');
			$this->data['tab_review'] = sprintf($this->text('tab_review','product@product'), $this->services->get_total_reviews_by_product_id($this->request->get['product_id']));
			$this->data['tab_related'] = $this->text('tab_related','product@product');
				
			$this->data['product_id'] = $this->request->get['product_id'];
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];
				
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->setting->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->text('text_instock','product@product');
			}
				
			if ($product_info['image']) {
				$this->data['popup'] = $this->services->resize($product_info['image'], $this->setting->get('config_image_popup_width'), $this->setting->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}
				
			if ($product_info['image']) {
				$this->data['thumb'] = $this->services->resize($product_info['image'], $this->setting->get('config_image_thumb_width'), $this->setting->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}
				
			$this->data['images'] = array();
				
			$results = $this->services->get_product_images($this->request->get['product_id']);
				
			foreach ($results as $result) {
				$this->data['images'][] = array(
						'popup' => $this->services->resize($result['image'], $this->setting->get('config_image_popup_width'), $this->setting->get('config_image_popup_height')),
						'thumb' => $this->services->resize($result['image'], $this->setting->get('config_image_additional_width'), $this->setting->get('config_image_additional_height'))
				);
			}
		
			if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
				//$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->setting->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}
			/// SILINCEK
			$this->data['price'] = 22;
				
		
			if ((float)$product_info['special']) {
				//$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->setting->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
			/// SILINCEK
			$this->data['special'] = 18;
				
			if ($this->setting->get('config_tax')) {
				//$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
			/// SILINCEK
			$this->data['tax'] = 8;
				
			$discounts = $this->services->get_product_discounts($this->request->get['product_id']);
				
			$this->data['discounts'] = array();
				
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
						'quantity' => $discount['quantity'],
						'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->setting->get('config_tax')))
				);
			}
				
			$this->data['options'] = array();
				
			foreach ($this->services->get_product_options($this->request->get['product_id']) as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = array();
						
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->setting->get('config_tax')));
							} else {
								$price = false;
							}
								
							$option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->services->resize($option_value['image'], 50, 50),
									'price'                   => $price,
									'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
						
					$this->data['options'][] = array(
							'product_option_id' => $option['product_option_id'],
							'option_id'         => $option['option_id'],
							'name'              => $option['name'],
							'type'              => $option['type'],
							'option_value'      => $option_value_data,
							'required'          => $option['required']
					);
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
							'product_option_id' => $option['product_option_id'],
							'option_id'         => $option['option_id'],
							'name'              => $option['name'],
							'type'              => $option['type'],
							'option_value'      => $option['option_value'],
							'required'          => $option['required']
					);
				}
			}
				
			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}
				
			$this->data['review_status'] = $this->setting->get('config_review_status');
			$this->data['reviews'] = sprintf($this->text('text_reviews','product@product'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->services->get_product_attributes($this->request->get['product_id']);
				
			$this->data['products'] = array();
				
			$results = $this->services->get_product_related($this->request->get['product_id']);
				
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->services->resize($result['image'], $this->setting->get('config_image_related_width'), $this->setting->get('config_image_related_height'));
				} else {
					$image = false;
				}
		
				if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
					//$price = $SHOP->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$price = false;
				}
		
				if ((float)$result['special']) {
					//$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$special = false;
				}
		
				if ($this->setting->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['products'][] = array(
						'product_id' => $result['product_id'],
						'thumb'   	 => $image,
						'name'    	 => $result['name'],
						'price'   	 => $price,
						'special' 	 => $special,
						'rating'     => $rating,
						'reviews'    => sprintf($this->text('text_reviews','product@product'), (int)$result['reviews']),
						'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
				
			$this->data['tags'] = array();
				
			$tags = explode(',', $product_info['tag']);
				
			foreach ($tags as $tag) {
				$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'filter_tag=' . trim($tag))
				);
			}
				
			$this->services->update_product_viewed($this->request->get['product_id']);
				
		} else {
			$url = '';
				
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
				
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
				
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
				
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
				
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
		
			$this->data['breadcrumbs'][] = array(
					'text'      => $this->text('text_error','product@product'),
					'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
					'separator' => $this->text('text_separator','common@basic')
			);
		
			$this->document->setTitle($this->text('text_error','product@product'));
		
			$this->data['heading_title'] = $this->text('text_error','product@product');
		
			$this->data['text_error'] = $this->text('text_error','product@product');
		
			$this->data['button_continue'] = $this->text('button_continue','common@basic');
		
			$this->data['continue'] = $this->url->link('common/home');
		
		}
		
		
	} 	
}
?>