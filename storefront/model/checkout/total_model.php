<?php
class total_model extends Model {	
	
	public function getTotal($mode, &$total_data, &$total, &$taxes) {
		if($mode=='coupon') return $this->getCouponTotal(&$total_data, &$total, &$taxes);
		if($mode=='credit') return $this->getCreditTotal(&$total_data, &$total, &$taxes);
		if($mode=='handling') return $this->getHandingTotal(&$total_data, &$total, &$taxes);
		if($mode=='klarna_fee') return $this->getKlarnaFeeTotal(&$total_data, &$total, &$taxes);
		if($mode=='low_order_fee') return $this->getLowOrderFeeTotal(&$total_data, &$total, &$taxes);
		if($mode=='reward') return $this->getRewardTotal(&$total_data, &$total, &$taxes);
		if($mode=='shipping') return $this->getShippingTotal(&$total_data, &$total, &$taxes);
		if($mode=='sub_total') return $this->getSubTotalTotal(&$total_data, &$total, &$taxes);
		if($mode=='tax') return $this->getTaxTotal(&$total_data, &$total, &$taxes);
		if($mode=='total') return $this->getTotalTotal(&$total_data, &$total, &$taxes);
		if($mode=='voucher') return $this->getVoucherTotal(&$total_data, &$total, &$taxes);
	}
	
	
	public function getCouponTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session['coupon'])) {
	
			$coupon_info = $this->services->get_coupon($this->session['coupon']);
				
			if ($coupon_info) {
				$discount_total = 0;
	
				if (!$coupon_info['product']) {
					$sub_total = $this->cart->getSubTotal();
				} else {
					$sub_total = 0;
	
					foreach ($this->cart->getProducts() as $product) {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$sub_total += $product['total'];
						}
					}
				}
	
				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}
	
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
						
					if (!$coupon_info['product']) {
						$status = true;
					} else {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$status = true;
						} else {
							$status = false;
						}
					}
						
					if ($status) {
						if ($coupon_info['type'] == 'F') {
							$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($coupon_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon_info['discount'];
						}
	
						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);
								
							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}
						
					$discount_total += $discount;
				}
	
				if ($coupon_info['shipping'] && isset($this->session['shipping_method'])) {
					if (!empty($this->session['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session['shipping_method']['cost'], $this->session['shipping_method']['tax_class_id']);
	
						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}
						
					$discount_total += $this->session['shipping_method']['cost'];
				}
				 
				$total_data[] = array(
						'code'       => 'coupon',
						'title'      => sprintf($this->text('text_coupon'), $this->session['coupon']),
						'text'       => $this->currency->format(-$discount_total),
						'value'      => -$discount_total,
						'sort_order' => $this->setting->get('coupon_sort_order')
				);
	
				$total -= $discount_total;
			}
		}
	}

	public function confirmCoupon($order_info, $order_total) {
		$code = '';
	
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
	
		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}
	
		$coupon_info = $this->services->get_coupon($code);
		
		if ($coupon_info) {
			$this->services->redeem($coupon_info['coupon_id'], $order_info['order_id'], $order_info['customer_id'], $order_total['value']);
				
		}
	}	
	
	
	

	public function getCreditTotal(&$total_data, &$total, &$taxes) {
		if ($this->setting->get('credit_status')) {
				
			$balance = $this->customer->getBalance();
				
			if ((float)$balance) {
				if ($balance > $total) {
					$credit = $total;
				} else {
					$credit = $balance;
				}
	
				if ($credit > 0) {
					$total_data[] = array(
							'code'       => 'credit',
							'title'      => $this->text('text_credit'),
							'text'       => $this->currency->format(-$credit),
							'value'      => -$credit,
							'sort_order' => $this->setting->get('credit_sort_order')
					);
						
					$total -= $credit;
				}
			}
		}
	}
	
	public function confirmCredit($order_info, $order_total) {
	
		if ($order_info['customer_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->text('text_order_id'), (int)$order_info['order_id'])) . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
		}
	}
	

	public function getHandlingTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < $this->setting->get('handling_total')) && ($this->cart->getSubTotal() > 0)) {
	
			$total_data[] = array(
					'code'       => 'handling',
					'title'      => $this->text('text_handling'),
					'text'       => $this->currency->format($this->setting->get('handling_fee')),
					'value'      => $this->setting->get('handling_fee'),
					'sort_order' => $this->setting->get('handling_sort_order')
			);
	
			if ($this->setting->get('handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->setting->get('handling_fee'), $this->setting->get('handling_tax_class_id'));
	
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
				
			$total += $this->setting->get('handling_fee');
		}
	}
	
	
	public function getKlarnaFeeTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < $this->setting->get('klarna_fee_total')) && ($this->cart->getSubTotal() > 0) && isset($this->session['payment_method']['code']) && $this->session['payment_method']['code'] == 'klarna_invoice' || $this->session['payment_method']['code'] == 'klarna_pp') {
	
			$total_data[] = array(
					'code'       => 'klarna_fee',
					'title'      => $this->text('text_klarna_fee'),
					'text'       => $this->currency->format($this->setting->get('klarna_fee_fee')),
					'value'      => $this->setting->get('klarna_fee_fee'),
					'sort_order' => $this->setting->get('klarna_fee_sort_order')
			);
	
			if ($this->setting->get('klarna_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->setting->get('klarna_fee_fee'), $this->setting->get('klarna_fee_tax_class_id'));
	
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
				
			$total += $this->setting->get('klarna_fee_fee');
		}
	}	
	
	public function getLowOrderFeeTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < $this->setting->get('low_order_fee_total'))) {
	
			$total_data[] = array(
					'code'       => 'low_order_fee',
					'title'      => $this->text('text_low_order_fee'),
					'text'       => $this->currency->format($this->setting->get('low_order_fee_fee')),
					'value'      => $this->setting->get('low_order_fee_fee'),
					'sort_order' => $this->setting->get('low_order_fee_sort_order')
			);
				
			if ($this->setting->get('low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->setting->get('low_order_fee_fee'), $this->setting->get('low_order_fee_tax_class_id'));
	
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
				
			$total += $this->setting->get('low_order_fee_fee');
		}
	}	
	
	public function getRewardTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session['reward'])) {
				
			$points = $this->customer->getRewardPoints();
				
			if ($this->session['reward'] <= $points) {
				$discount_total = 0;
	
				$points_total = 0;
	
				foreach ($this->cart->getProducts() as $product) {
					if ($product['points']) {
						$points_total += $product['points'];
					}
				}
	
				$points = min($points, $points_total);
	
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
						
					if ($product['points']) {
						$discount = $product['total'] * ($this->session['reward'] / $points_total);
	
						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);
								
							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}
						
					$discount_total += $discount;
				}
					
				$total_data[] = array(
						'code'       => 'reward',
						'title'      => sprintf($this->text('text_reward'), $this->session['reward']),
						'text'       => $this->currency->format(-$discount_total),
						'value'      => -$discount_total,
						'sort_order' => $this->setting->get('reward_sort_order')
				);
	
				$total -= $discount_total;
			}
		}
	}
	
	public function confirmReward($order_info, $order_total) {
	
		$points = 0;
	
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
	
		if ($start && $end) {
			$points = substr($order_total['title'], $start, $end - $start);
		}
	
		if ($points) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$order_info['customer_id'] . "', description = '" . $this->db->escape(sprintf($this->text('text_order_id'), (int)$order_info['order_id'])) . "', points = '" . (float)-$points . "', date_added = NOW()");
		}
	}	
	
	
	public function getShippingTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->hasShipping() && isset($this->session['shipping_method'])) {
			$total_data[] = array(
					'code'       => 'shipping',
					'title'      => $this->session['shipping_method']['title'],
					'text'       => $this->currency->format($this->session['shipping_method']['cost']),
					'value'      => $this->session['shipping_method']['cost'],
					'sort_order' => $this->setting->get('shipping_sort_order')
			);
	
			if ($this->session['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session['shipping_method']['cost'], $this->session['shipping_method']['tax_class_id']);
	
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
				
			$total += $this->session['shipping_method']['cost'];
		}
	}
	
	
	public function getSubTotalTotal(&$total_data, &$total, &$taxes) {
	
		$sub_total = $this->cart->getSubTotal();
	
		if (isset($this->session['vouchers']) && $this->session['vouchers']) {
			foreach ($this->session['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}
	
		$total_data[] = array(
				'code'       => 'sub_total',
				'title'      => $this->text('text_sub_total'),
				'text'       => $this->currency->format($sub_total),
				'value'      => $sub_total,
				'sort_order' => $this->setting->get('sub_total_sort_order')
		);
	
		$total += $sub_total;
	}
	
	public function getTaxTotal(&$total_data, &$total, &$taxes) {
		foreach ($taxes as $key => $value) {
			if ($value > 0) {
				$total_data[] = array(
						'code'       => 'tax',
						'title'      => $this->tax->getRateName($key),
						'text'       => $this->currency->format($value),
						'value'      => $value,
						'sort_order' => $this->setting->get('tax_sort_order')
				);
	
				$total += $value;
			}
		}
	}
	
	
	public function getTotalTotal(&$total_data, &$total, &$taxes) {
	
		$total_data[] = array(
				'code'       => 'total',
				'title'      => $this->text('text_total'),
				'text'       => $this->currency->format(max(0, $total)),
				'value'      => max(0, $total),
				'sort_order' => $this->setting->get('total_sort_order')
		);
	}
	
	public function getVoucherTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session['voucher'])) {
				
			$voucher_info = $this->services->get_voucher($this->session['voucher']);
				
			if ($voucher_info) {
				if ($voucher_info['amount'] > $total) {
					$amount = $total;
				} else {
					$amount = $voucher_info['amount'];
				}
				 
				$total_data[] = array(
						'code'       => 'voucher',
						'title'      => sprintf($this->text('text_voucher'), $this->session['voucher']),
						'text'       => $this->currency->format(-$amount),
						'value'      => -$amount,
						'sort_order' => $this->setting->get('voucher_sort_order')
				);
	
				$total -= $amount;
			}
		}
	}
	
	public function confirmVoucher($order_info, $order_total) {
		$code = '';
	
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
	
		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}
	
		$this->load->model('checkout/voucher');
	
		$voucher_info = $this->model_checkout_voucher->getVoucher($code);
	
		if ($voucher_info) {
			$this->model_checkout_voucher->redeem($voucher_info['voucher_id'], $order_info['order_id'], $order_total['value']);
		}
	}	
	
}
?>