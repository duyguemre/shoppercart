<?php   
class shopping_cart extends Module {
	
	public function process() {
	
		if (!isset($this->session['vouchers'])) {
			$this->session['vouchers'] = array();
		}
	
		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}

				
			unset($this->session['shipping_method']);
			unset($this->session['shipping_methods']);
			unset($this->session['payment_method']);
			unset($this->session['payment_methods']);
			unset($this->session['reward']);
				
				
			$this->services->redirect($this->url->link('checkout/cart'));
		}
	
		// Remove
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);
				
			unset($this->session['vouchers'][$this->request->get['remove']]);
				
			$this->session['success'] = $this->text('text_remove');
	
			unset($this->session['shipping_method']);
			unset($this->session['shipping_methods']);
			unset($this->session['payment_method']);
			unset($this->session['payment_methods']);
			unset($this->session['reward']);
	
			$this->services->redirect($this->url->link('checkout/cart'));
		}
			
		// Coupon
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session['coupon'] = $this->request->post['coupon'];
	
			$this->session['success'] = $this->text('text_coupon');
				
			$this->services->redirect($this->url->link('checkout/cart'));
		}
	
		// Voucher
		if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
			$this->session['voucher'] = $this->request->post['voucher'];
	
			$this->session['success'] = $this->text('text_voucher');
	
			$this->services->redirect($this->url->link('checkout/cart'));
		}
	
		// Reward
		if (isset($this->request->post['reward']) && $this->validateReward()) {
			$this->session['reward'] = abs($this->request->post['reward']);
	
			$this->session['success'] = $this->text('text_reward');
	
			$this->services->redirect($this->url->link('checkout/cart'));
		}
	
		// Shipping
		if (isset($this->request->post['shipping_method']) && $this->validateShipping()) {
			$shipping = explode('.', $this->request->post['shipping_method']);
				
			$this->session['shipping_method'] = $this->session['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				
			$this->session['success'] = $this->text('text_shipping');
				
			$this->services->redirect($this->url->link('checkout/cart'));
		}
	
		$this->document->setTitle($this->text('heading_title'));
	
			
		if ($this->cart->hasProducts() || !empty($this->session['vouchers'])) {
			$points = $this->customer->getRewardPoints();
				
			$points_total = 0;
				
			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}
	
			$this->data['heading_title'] = $this->text('heading_title');
				
			$this->data['text_next'] = $this->text('text_next');
			$this->data['text_next_choice'] = $this->text('text_next_choice');
			$this->data['text_use_coupon'] = $this->text('text_use_coupon');
			$this->data['text_use_voucher'] = $this->text('text_use_voucher');
			$this->data['text_use_reward'] = sprintf($this->text('text_use_reward'), $points);
			$this->data['text_shipping_estimate'] = $this->text('text_shipping_estimate');
			$this->data['text_shipping_detail'] = $this->text('text_shipping_detail');
			$this->data['text_shipping_method'] = $this->text('text_shipping_method');
			$this->data['text_select'] = $this->text('text_select');
			$this->data['text_none'] = $this->text('text_none');
	
			$this->data['column_image'] = $this->text('column_image');
			$this->data['column_name'] = $this->text('column_name');
			$this->data['column_model'] = $this->text('column_model');
			$this->data['column_quantity'] = $this->text('column_quantity');
			$this->data['column_price'] = $this->text('column_price');
			$this->data['column_total'] = $this->text('column_total');
				
			$this->data['entry_coupon'] = $this->text('entry_coupon');
			$this->data['entry_voucher'] = $this->text('entry_voucher');
			$this->data['entry_reward'] = sprintf($this->text('entry_reward'), $points_total);
			$this->data['entry_country'] = $this->text('entry_country');
			$this->data['entry_zone'] = $this->text('entry_zone');
			$this->data['entry_postcode'] = $this->text('entry_postcode');
	
			$this->data['button_update'] = $this->text('button_update');
			$this->data['button_remove'] = $this->text('button_remove');
			$this->data['button_coupon'] = $this->text('button_coupon');
			$this->data['button_voucher'] = $this->text('button_voucher');
			$this->data['button_reward'] = $this->text('button_reward');
			$this->data['button_quote'] = $this->text('button_quote');
			$this->data['button_shipping'] = $this->text('button_shipping');
			$this->data['button_shopping'] = $this->text('button_shopping');
			$this->data['button_checkout'] = $this->text('button_checkout');
				
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} elseif (!$this->cart->hasStock() && (!$this->setting->get('config_stock_checkout') || $this->setting->get('config_stock_warning'))) {
				$this->data['error_warning'] = $this->text('error_stock');
			} else {
				$this->data['error_warning'] = '';
			}
				
			if ($this->setting->get('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf($this->text('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$this->data['attention'] = '';
			}
	
			if (isset($this->session['success'])) {
				$this->data['success'] = $this->session['success'];
					
				unset($this->session['success']);
			} else {
				$this->data['success'] = '';
			}
				
			$this->data['action'] = $this->url->link('checkout/cart');
	
			if ($this->setting->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->setting->get('config_weight_class_id'), $this->text('decimal_point'), $this->text('thousand_point'));
			} else {
				$this->data['weight'] = '';
			}
				
			$this->data['products'] = array();
				
			$products = $this->cart->getProducts();
	
			foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}
	
				if ($product['minimum'] > $product_total) {
					$this->data['error_warning'] = sprintf($this->text('error_minimum'), $product['name'], $product['minimum']);
				}
					
				if ($product['image']) {
					$image = $this->services->resize($product['image'], $this->setting->get('config_image_cart_width'), $this->setting->get('config_image_cart_height'));
				} else {
					$image = '';
				}
	
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
	
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
						
					$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
	
				// Display prices
				if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$price = false;
				}
	
				// Display prices
				if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->setting->get('config_tax')) * $product['quantity']);
				} else {
					$total = false;
				}
	
				$this->data['products'][] = array(
						'key'      => $product['key'],
						'thumb'    => $image,
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'stock'    => $product['stock'] ? true : !(!$this->setting->get('config_stock_checkout') || $this->setting->get('config_stock_warning')),
						'reward'   => ($product['reward'] ? sprintf($this->text('text_points'), $product['reward']) : ''),
						'price'    => $price,
						'total'    => $total,
						'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
						'remove'   => $this->url->link('checkout/cart', 'remove=' . $product['key'])
				);
			}
				
			// Gift Voucher
			$this->data['vouchers'] = array();
				
			if (!empty($this->session['vouchers'])) {
				foreach ($this->session['vouchers'] as $key => $voucher) {
					$this->data['vouchers'][] = array(
							'key'         => $key,
							'description' => $voucher['description'],
							'amount'      => $this->currency->format($voucher['amount']),
							'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}
	
			if (isset($this->request->post['next'])) {
				$this->data['next'] = $this->request->post['next'];
			} else {
				$this->data['next'] = '';
			}
				
			$this->data['coupon_status'] = $this->setting->get('coupon_status');
				
			if (isset($this->request->post['coupon'])) {
				$this->data['coupon'] = $this->request->post['coupon'];
			} elseif (isset($this->session['coupon'])) {
				$this->data['coupon'] = $this->session['coupon'];
			} else {
				$this->data['coupon'] = '';
			}
				
			$this->data['voucher_status'] = $this->setting->get('voucher_status');
				
			if (isset($this->request->post['voucher'])) {
				$this->data['voucher'] = $this->request->post['voucher'];
			} elseif (isset($this->session['voucher'])) {
				$this->data['voucher'] = $this->session['voucher'];
			} else {
				$this->data['voucher'] = '';
			}
				
			$this->data['reward_status'] = ($points && $points_total && $this->setting->get('reward_status'));
				
			if (isset($this->request->post['reward'])) {
				$this->data['reward'] = $this->request->post['reward'];
			} elseif (isset($this->session['reward'])) {
				$this->data['reward'] = $this->session['reward'];
			} else {
				$this->data['reward'] = '';
			}
	
			$this->data['shipping_status'] = $this->setting->get('shipping_status') && $this->setting->get('shipping_estimator') && $this->cart->hasShipping();
	
			if (isset($this->request->post['country_id'])) {
				$this->data['country_id'] = $this->request->post['country_id'];
			} elseif (isset($this->session['shipping_country_id'])) {
				$this->data['country_id'] = $this->session['shipping_country_id'];
			} else {
				$this->data['country_id'] = $this->setting->get('config_country_id');
			}
	
			$this->data['countries'] = $this->services->get_countries();
	
			if (isset($this->request->post['zone_id'])) {
				$this->data['zone_id'] = $this->request->post['zone_id'];
			} elseif (isset($this->session['shipping_zone_id'])) {
				$this->data['zone_id'] = $this->session['shipping_zone_id'];
			} else {
				$this->data['zone_id'] = '';
			}
				
			if (isset($this->request->post['postcode'])) {
				$this->data['postcode'] = $this->request->post['postcode'];
			} elseif (isset($this->session['shipping_postcode'])) {
				$this->data['postcode'] = $this->session['shipping_postcode'];
			} else {
				$this->data['postcode'] = '';
			}
				
			if (isset($this->request->post['shipping_method'])) {
				$this->data['shipping_method'] = $this->request->post['shipping_method'];
			} elseif (isset($this->session['shipping_method'])) {
				$this->data['shipping_method'] = $this->session['shipping_method']['code'];
			} else {
				$this->data['shipping_method'] = '';
			}
	
			// Totals
				
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
				
			// Display prices
			if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
				$sort_order = array();
	
				$results = $this->services->get_extensions('total');
	
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->setting->get($value['code'] . '_sort_order');
				}
	
				array_multisort($sort_order, SORT_ASC, $results);
	
				foreach ($results as $result) {
					if ($this->setting->get($result['code'] . '_status')) {
						$this->services->get_total($result['code'], $total_data, $total, $taxes);
					}
						
					$sort_order = array();
	
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
	
					array_multisort($sort_order, SORT_ASC, $total_data);
				}
			}
				
			$this->data['totals'] = $total_data;
	
			$this->data['continue'] = $this->url->link('common/home');
	
			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
	
		} else {
			$this->data['heading_title'] = $this->text('heading_title');
	
			$this->data['text_error'] = $this->text('text_empty');
	
			$this->data['button_continue'] = $this->text('button_continue');
				
			$this->data['continue'] = $this->url->link('common/home');
	
			unset($this->session['success']);
	
		}
	}
	
	private function validateCoupon() {
		$this->load->model('checkout/coupon');
	
		$coupon_info = $this->services->get_coupon($this->request->post['coupon']);
	
		if (!$coupon_info) {
			$this->error['warning'] = $this->text('error_coupon');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validateVoucher() {
		$this->load->model('checkout/voucher');
	
		$voucher_info = $this->services->get_voucher($this->request->post['voucher']);
	
		if (!$voucher_info) {
			$this->error['warning'] = $this->text('error_voucher');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validateReward() {
		$points = $this->customer->getRewardPoints();
	
		$points_total = 0;
	
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}
	
		if (empty($this->request->post['reward'])) {
			$this->error['warning'] = $this->text('error_reward');
		}
	
		if ($this->request->post['reward'] > $points) {
			$this->error['warning'] = sprintf($this->text('error_points'), $this->request->post['reward']);
		}
	
		if ($this->request->post['reward'] > $points_total) {
			$this->error['warning'] = sprintf($this->text('error_maximum'), $points_total);
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validateShipping() {
		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);
				
			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->error['warning'] = $this->text('error_shipping');
			}
		} else {
			$this->error['warning'] = $this->text('error_shipping');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function add() {
		$this->language->load('checkout/cart');
	
		$json = array();
	
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
	
		$product_info = $this->services->get_product($product_id);
	
		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
	
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}
				
			$product_options = $this->services->get_product_options($this->request->post['product_id']);
				
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->text('error_required'), $product_option['name']);
				}
			}
				
			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option);
	
				$json['success'] = sprintf($this->text('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
	
				unset($this->session['shipping_method']);
				unset($this->session['shipping_methods']);
				unset($this->session['payment_method']);
				unset($this->session['payment_methods']);
	
				// Totals
				$this->load->model('setting/extension');
	
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
	
				// Display prices
				if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
					$sort_order = array();
						
					$results = $this->services->get_extensions('total');
						
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->setting->get($value['code'] . '_sort_order');
					}
						
					array_multisort($sort_order, SORT_ASC, $results);
						
					foreach ($results as $result) {
						if ($this->setting->get($result['code'] . '_status')) {
							$this->services->getTotal($result['code'], $total_data, $total, $taxes);
						}
	
						$sort_order = array();
							
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
							
						array_multisort($sort_order, SORT_ASC, $total_data);
					}
				}
	
				$json['total'] = sprintf($this->text('text_items'), $this->cart->countProducts() + (isset($this->session['vouchers']) ? count($this->session['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}
	
		$this->response->setOutput(json_encode($json));
	}
	
	public function quote() {
		$this->language->load('checkout/cart');
	
		$json = array();
	
		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = $this->text('error_product');
		}
	
		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf($this->text('error_no_shipping'), $this->url->link('information/contact'));
		}
	
		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = $this->text('error_country');
		}
	
		if ($this->request->post['zone_id'] == '') {
			$json['error']['zone'] = $this->text('error_zone');
		}
				
		$country_info = $this->services->get_country($this->request->post['country_id']);
	
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$json['error']['postcode'] = $this->text('error_postcode');
		}
	
		if (!$json) {
			$this->tax->setShippingAddress($this->request->post['country_id'], $this->request->post['zone_id']);
	
			// Default Shipping Address
			$this->session['shipping_country_id'] = $this->request->post['country_id'];
			$this->session['shipping_zone_id'] = $this->request->post['zone_id'];
			$this->session['shipping_postcode'] = $this->request->post['postcode'];
	
			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}
				
			$this->load->model('localisation/zone');
	
			$zone_info = $this->services->get_zone($this->request->post['zone_id']);
				
			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}
				
			$address_data = array(
					'firstname'      => '',
					'lastname'       => '',
					'company'        => '',
					'address_1'      => '',
					'address_2'      => '',
					'postcode'       => $this->request->post['postcode'],
					'city'           => '',
					'zone_id'        => $this->request->post['zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $this->request->post['country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format
			);
	
			$quote_data = array();
				
			$this->load->model('setting/extension');
				
			$results = $this->services->get_extensions('shipping');
				
			foreach ($results as $result) {
				if ($this->setting->get($result['code'] . '_status')) {
						
					//LOOOOOOOOOOOOOOOOOOOOOOOOOK
					$quote = 1;
					//$this->{'model_shipping_' . $result['code']}->getQuote($address_data);
	
					if ($quote) {
						$quote_data[$result['code']] = array(
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
						);
					}
				}
			}
	
			$sort_order = array();
	
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
				
			$this->session['shipping_methods'] = $quote_data;
				
			if ($this->session['shipping_methods']) {
				$json['shipping_method'] = $this->session['shipping_methods'];
			} else {
				$json['error']['warning'] = sprintf($this->text('error_no_shipping'), $this->url->link('information/contact'));
			}
		}
	
		$this->response->setOutput(json_encode($json));
	}
	
	public function country() {
		$json = array();
	
		$country_info = $this->services->get_country($this->request->get['country_id']);
	
		if ($country_info) {
	
			$json = array(
					'country_id'        => $country_info['country_id'],
					'name'              => $country_info['name'],
					'iso_code_2'        => $country_info['iso_code_2'],
					'iso_code_3'        => $country_info['iso_code_3'],
					'address_format'    => $country_info['address_format'],
					'postcode_required' => $country_info['postcode_required'],
					'zone'              => $this->services->get_zones_by_country_id($this->request->get['country_id']),
					'status'            => $country_info['status']
			);
		}
	
		$this->response->setOutput(json_encode($json));
	}	
}
?>