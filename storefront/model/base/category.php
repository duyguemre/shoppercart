<?php
class category extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->setting->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->setting->get('config_store_id') . "' AND c.status = '1'");
	
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->setting->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->setting->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
	
		return $query->rows;
	}
	
	public function getCategoriesByParentId($category_id) {
		$category_data = array();
	
		$category_query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");
	
		foreach ($category_query->rows as $category) {
			$category_data[] = $category['category_id'];
	
			$children = $this->getCategoriesByParentId($category['category_id']);
	
			if ($children) {
				$category_data = array_merge($children, $category_data);
			}
		}
	
		return $category_data;
	}
		
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->setting->get('config_store_id') . "'");
	
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->setting->get('config_layout_category');
		}
	}
		
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->setting->get('config_store_id') . "' AND c.status = '1'");
	
		return $query->row['total'];
	}
	
	
	
	
	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND m2s.store_id = '" . (int)$this->setting->get('config_store_id') . "'");
	
		return $query->row;
	}
	
	public function getManufacturers($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->setting->get('config_store_id') . "'";
				
			$sort_data = array(
					'name',
					'sort_order'
			);
				
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY name";
			}
				
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
				
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
					
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
				
			$query = $this->db->query($sql);
				
			return $query->rows;
		} else {
			$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->setting->get('config_store_id'));
	
			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->setting->get('config_store_id') . "' ORDER BY name");
	
				$manufacturer_data = $query->rows;
					
				$this->cache->set('manufacturer.' . (int)$this->setting->get('config_store_id'), $manufacturer_data);
			}
				
			return $manufacturer_data;
		}
	}
	
	
}
?>