<?php
class shop_model extends Model {
	public function getPositions() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . ".shop_position");	
		return $query->rows;
	}
	public function getLayoutCode($layout_id) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . ".shop_layout where id=".$layout_id);			
		return $query->row["code"];
	}
	public function getPageModules($page) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX . ".shop_page p left join ". DB_PREFIX . ".shop_page_module_position spmp on p.id=spmp.page_id left join ". DB_PREFIX . ".shop_position sp on spmp.position_id=sp.id left join ". DB_PREFIX . ".shop_module sm on spmp.module_id=sm.id where p.code='".$page."' and sm.module_status=1 order by spmp.row asc");			
		//echo "SELECT * FROM ". DB_PREFIX . ".shop_page p left join ". DB_PREFIX . ".shop_page_module_position spmp on p.id=spmp.page_id left join ". DB_PREFIX . ".shop_position sp on spmp.position_id=sp.id left join ". DB_PREFIX . ".shop_module sm on spmp.module_id=sm.id where p.code='".$page."' and sm.module_status=1 order by spmp.row asc";
		return $query->rows;
	}	
	public function getModule($module_code) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX . ".shop_module sm where sm.module_code='".$module_code."'");
		return $query->row;
	}
	
	public function getSettings() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
		return $query->rows;		
	}
	
	public function getLanguages() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");
		return $query->rows;		
	}
	
	public function getInformations() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE id.language_id = '1' AND i2s.store_id = '0' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC");
	
		return $query->rows;
	}
	
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");	
		return $query->rows;
	}
}
?>