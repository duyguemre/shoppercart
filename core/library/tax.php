<?php
final class Tax extends system{
	private $shipping_address;
	private $payment_address;
	private $store_address;
	
	public function __construct($system) {		
		$this->initialize($system);
		
		if (isset($this->session['shipping_country_id']) || isset($this->session['shipping_zone_id'])) {
			$this->setShippingAddress($this->session['shipping_country_id'], $this->session['shipping_zone_id']);
		} elseif ($this->setting->get('config_tax_default') == 'shipping') {
			$this->setShippingAddress($this->setting->get('config_country_id'), $this->setting->get('config_zone_id'));
		}
		
		if (isset($this->session['payment_country_id']) || isset($this->session['payment_zone_id'])) {
			$this->setPaymentAddress($this->session['payment_country_id'], $this->session['payment_zone_id']);
		} elseif ($this->setting->get('config_tax_default') == 'payment') {
			$this->setPaymentAddress($this->setting->get('config_country_id'), $this->setting->get('config_zone_id'));
		}
		
		$this->setStoreAddress($this->setting->get('config_country_id'), $this->setting->get('config_zone_id'));			
  	}
	
	public function setShippingAddress($country_id, $zone_id) {
		$this->shipping_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);				
	}

	public function setPaymentAddress($country_id, $zone_id) {
		$this->payment_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setStoreAddress($country_id, $zone_id) {
		$this->store_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}
							
  	public function calculate($value, $tax_class_id, $calculate = true) {
		if ($tax_class_id && $calculate) {
			$amount = $this->getTax($value, $tax_class_id);
				
			return $value + $amount;
		} else {
      		return $value;
    	}
  	}
	
  	public function getTax($value, $tax_class_id) {
		$amount = 0;
			
		$tax_rates = $this->getRates($value, $tax_class_id);
		
		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}
				
		return $amount;
  	}
		
	public function getRateName($tax_rate_id) {
		$tax_query = $this->services->get_tax_by_rate($tax_rate_id);
	
		if ($tax_query->num_rows) {
			return $tax_query->row['name'];
		} else {
			return false;
		}
	}
	
    public function getRates($value, $tax_class_id) {
		$tax_rates = array();
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}
				
		if ($this->shipping_address) {
			$tax_query = $this->services->get_tax_by_address($tax_class_id,$customer_group_id, $this->shipping_address['country_id'],$this->shipping_address['zone_id']);
			
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}

		if ($this->payment_address) {
			$tax_query = $this->services->get_tax_by_address($tax_class_id,$customer_group_id, $this->payment_address['country_id'],$this->payment_address['zone_id']);
			
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}
		
		if ($this->store_address) {
			$tax_query = $this->services->get_tax_by_address($tax_class_id,$customer_group_id, $this->store_address['country_id'],$this->store_address['zone_id']);
						
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}			
		
		$tax_rate_data = array();
		
		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}
			
			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}
		
			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}
		
		return $tax_rate_data;
	}

  	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>