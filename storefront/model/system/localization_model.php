<?php
class localization_model extends Model {
	public function getCurrencyByCode($currency) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");
	
		return $query->row;
	}

	public function getAllCurrencies() {
						
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
		return $query;
	}
	
	public function getCurrencies() {
		
		$currency_data = "";

		$currency_data = $this->cache->get('currency');
		if (!$currency_data) {
			$currency_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
	
			foreach ($query->rows as $result) {
      			$currency_data[$result['code']] = array(
        			'currency_id'   => $result['currency_id'],
        			'title'         => $result['title'],
        			'code'          => $result['code'],
					'symbol_left'   => $result['symbol_left'],
					'symbol_right'  => $result['symbol_right'],
					'decimal_place' => $result['decimal_place'],
					'value'         => $result['value'],
					'status'        => $result['status'],
					'date_modified' => $result['date_modified']
      			);
    		}	
			
			$this->cache->set('currency', $currency_data);
		}
			
		return $currency_data;	
	}	
	
	
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");
	
		return $query->row;
	}
	
	public function getCountries() {
		
		$country_data = $this->cache->get('country.status');
	
		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");
	
			$country_data = $query->rows;
	
			$this->cache->set('country.status', $country_data);
		}
	
		return $country_data;
	}	
	
	
	
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");
	
		return $query->row;
	}
	
	public function getZonesByCountryId($country_id) {
		
		$zone_data = $this->cache->get('zone.' . (int)$country_id);
	
		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");
	
			$zone_data = $query->rows;
				
			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}
	
		return $zone_data;
	}

	
	public function getLanguage($language_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
	
		return $query->row;
	}
	
	public function getLanguages() {
		
		$language_data = $this->cache->get('language');
	
		if (!$language_data) {
			$language_data = array();
				
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
	
			foreach ($query->rows as $result) {
				$language_data[$result['language_id']] = array(
						'language_id' => $result['language_id'],
						'name'        => $result['name'],
						'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'filename'    => $result['filename'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
				);
			}
				
			$this->cache->set('language', $language_data);
		}
	
		return $language_data;
	}	
}
?>