<?php
class tax_model extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");	
		return $query->row;
	}		

	
	public function getTaxByRate($tax_rate_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");		
		return $query->row;
	}
	

	public function getTaxByAddress($tax_class_id,$customer_group_id, $country_id,$zone_id) {
		$query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int)$customer_group_id . "' AND z2gz.country_id = '" . (int)$country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') ORDER BY tr1.priority ASC");
		return $query;
	}
	
	
}
?>