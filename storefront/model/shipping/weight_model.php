<?php 
class weight_model extends Model {    
	
	public function getGeoZones() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");		
		return $query->row;
	}
	
	public function getWeightClasses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->setting->get('config_language_id') . "'");
		return $query;
	}
	
  	public function getQuote($address) {
		
		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");
	
		foreach ($query->rows as $result) {
			if ($this->setting->get('weight_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		
			if ($status) {
				$cost = '';
				$weight = $this->cart->getWeight();
				
				$rates = explode(',', $this->setting->get('weight_' . $result['geo_zone_id'] . '_rate'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				if ((string)$cost != '') { 
					$quote_data['weight_' . $result['geo_zone_id']] = array(
						'code'         => 'weight.weight_' . $result['geo_zone_id'],
						'title'        => $result['name'] . '  (' . $this->text('text_weight') . ' ' . $this->weight->format($weight, $this->setting->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->setting->get('weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->setting->get('weight_tax_class_id'), $this->setting->get('config_tax')))
					);	
				}
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'code'       => 'weight',
        		'title'      => $this->text('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->setting->get('weight_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
  	}
  	
  	 
  	
}
?>