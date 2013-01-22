<?php
class product extends Model {

	public function resize($filename, $width, $height) {
		if (!file_exists(IMAGE_DIR . $filename) || !is_file(IMAGE_DIR . $filename)) {
			return;
		}		
		$info = pathinfo($filename);
		$extension = $info['extension'];
	
		$old_image = $filename;		
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
	
		if (!file_exists(IMAGE_DIR . $new_image) || (filemtime(IMAGE_DIR . $old_image) > filemtime(IMAGE_DIR . $new_image))) {
			$path = '';
				
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
				
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
	
				if (!file_exists(IMAGE_DIR . $path)) {
					@mkdir(IMAGE_DIR . $path, 0777);
				}
			}
	
			list($width_orig, $height_orig) = getimagesize(IMAGE_DIR . $old_image);
	
			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(IMAGE_DIR . $old_image);
				$image->resize($width, $height);
				$image->save(IMAGE_DIR . $new_image);
			} else {
				copy(IMAGE_DIR . $old_image, IMAGE_DIR . $new_image);
			}
		}
	
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return HTTPS_IMAGE . $new_image;
		} else {
			return HTTP_IMAGE . $new_image;
		}
	}
	public function getProduct($product_id) {
		$customer_group_id = 0;
	
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '1') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '1') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '1') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '1' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '0'");

		if ($query->num_rows) {
			return array(
					'product_id'       => $query->row['product_id'],
					'name'             => $query->row['name'],
					'description'      => $query->row['description'],
					'meta_description' => $query->row['meta_description'],
					'meta_keyword'     => $query->row['meta_keyword'],
					'tag'              => $query->row['tag'],
					'model'            => $query->row['model'],
					'sku'              => $query->row['sku'],
					'upc'              => $query->row['upc'],
					'ean'              => $query->row['ean'],
					'jan'              => $query->row['jan'],
					'isbn'             => $query->row['isbn'],
					'mpn'              => $query->row['mpn'],
					'location'         => $query->row['location'],
					'quantity'         => $query->row['quantity'],
					'stock_status'     => $query->row['stock_status'],
					'image'            => $query->row['image'],
					'manufacturer_id'  => $query->row['manufacturer_id'],
					'manufacturer'     => $query->row['manufacturer'],
					'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
					'special'          => $query->row['special'],
					'reward'           => $query->row['reward'],
					'points'           => $query->row['points'],
					'tax_class_id'     => $query->row['tax_class_id'],
					'date_available'   => $query->row['date_available'],
					'weight'           => $query->row['weight'],
					'weight_class_id'  => $query->row['weight_class_id'],
					'length'           => $query->row['length'],
					'width'            => $query->row['width'],
					'height'           => $query->row['height'],
					'length_class_id'  => $query->row['length_class_id'],
					'subtract'         => $query->row['subtract'],
					'rating'           => round($query->row['rating']),
					'reviews'          => $query->row['reviews'],
					'minimum'          => $query->row['minimum'],
					'sort_order'       => $query->row['sort_order'],
					'status'           => $query->row['status'],
					'date_added'       => $query->row['date_added'],
					'date_modified'    => $query->row['date_modified'],
					'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	
	
	public function getTotalProducts($data = array()) {
		
	/*	if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}
	*/
		$customer_group_id = $this->setting->get('config_customer_group_id');

			
		$cache = md5(http_build_query($data));
	
		$product_data = $this->cache->get('product.total.' . 1 . '.' . 0 . '.' . (int)$customer_group_id . '.' . $cache);
	
		if (!$product_data) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
	
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
	
			$sql .= " WHERE pd.language_id = '" . 1 . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . 0 . "'";
				
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
	
				if (!empty($data['filter_name'])) {
					if (!empty($data['filter_description'])) {
						$sql .= "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR MATCH(pd.description) AGAINST('" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "')";
					} else {
						$sql .= "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
					}
				}
	
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
	
				if (!empty($data['filter_tag'])) {
					$sql .= "MATCH(pd.tag) AGAINST('" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "')";
				}
					
				$sql .= ")";
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
			}
	
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
						
					$implode_data[] = (int)$data['filter_category_id'];
						
						
					$categories = $this->services->get_categories_by_parentId($data['filter_category_id']);
	
					foreach ($categories as $category_id) {
						$implode_data[] = (int)$category_id;
					}
	
					$sql .= " AND p2c.category_id IN (" . implode(', ', $implode_data) . ")";
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}
				
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
				
			$query = $this->db->query($sql);
				
			$product_data = $query->row['total'];
				
			$this->cache->set('product.total.' . 1 . '.' . 0 . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
		}
	
		return $product_data;
	}
	
	
	
	public function getProducts($data = array()) {
		
		/*
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}
	*/
			$customer_group_id = $this->setting->get('config_customer_group_id');
			
			
		$cache = md5(http_build_query($data));
	
		$product_data = $this->cache->get('product.' . (int)$this->setting->get('config_language_id') . '.' . (int)$this->setting->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
	
		if (!$product_data) {
			$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
	
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
				
			$sql .= " WHERE pd.language_id = '" . (int)$this->setting->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->setting->get('config_store_id') . "'";
				
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
	
				if (!empty($data['filter_name'])) {
					if (!empty($data['filter_description'])) {
						$sql .= "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR MATCH(pd.description) AGAINST('" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "')";
					} else {
						$sql .= "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
					}
				}
	
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
	
				if (!empty($data['filter_tag'])) {
					$sql .= "MATCH(pd.tag) AGAINST('" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "')";
				}
					
				$sql .= ")";
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
	
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
			}
				
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
						
					$implode_data[] = (int)$data['filter_category_id'];
						
						
					$categories = $this->model("category@base")->getCategoriesByParentId($data['filter_category_id']);
	
					foreach ($categories as $category_id) {
						$implode_data[] = (int)$category_id;
					}
	
					$sql .= " AND p2c.category_id IN (" . implode(', ', $implode_data) . ")";
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}
				
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
				
			$sql .= " GROUP BY p.product_id";
				
			$sort_data = array(
					'pd.name',
					'p.model',
					'p.quantity',
					'p.price',
					'rating',
					'p.sort_order',
					'p.date_added'
			);
				
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
				
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
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
				
			$product_data = array();
				
			$query = $this->db->query($sql);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
				
			$this->cache->set('product.' . (int)$this->setting->get('config_language_id') . '.' . (int)$this->setting->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
		}
	
		return $product_data;
	}
	
	
	
	public function addReview($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->dbescape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->dbescape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
	
	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
	
		if ($limit < 1) {
			$limit = 20;
		}
	
		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->setting->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
		return $query->rows;
	}
	
	public function getAverageRating($product_id) {
		
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review WHERE status = '1' AND product_id = '" . (int)$product_id . "' GROUP BY product_id");
	
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
	
	public function getTotalReviews() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");
	
		return $query->row['total'];
	}
	
	public function getTotalReviewsByProductId($product_id) {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->setting->get('config_language_id') . "'");
	
		return $query->row['total'];
	}

	
	
	public function getProductImages($product_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
	
		return $query->rows;
	}
	
	
	public function getProductDiscounts($product_id) {
/*		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}
	*/
		
		$customer_group_id = $this->setting->get('config_customer_group_id');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");
	
		return $query->rows;
	}

	
	public function getProductOptions($product_id) {
		$product_option_data = array();

		
		
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->setting->get('config_language_id') . "' ORDER BY o.sort_order");
	
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
					
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->setting->get('config_language_id') . "' ORDER BY ov.sort_order");
	
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $product_option_value['name'],
							'image'                   => $product_option_value['image'],
							'quantity'                => $product_option_value['quantity'],
							'subtract'                => $product_option_value['subtract'],
							'price'                   => $product_option_value['price'],
							'price_prefix'            => $product_option_value['price_prefix'],
							'weight'                  => $product_option_value['weight'],
							'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
					
				$product_option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $product_option['name'],
						'type'              => $product_option['type'],
						'option_value'      => $product_option_value_data,
						'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $product_option['name'],
						'type'              => $product_option['type'],
						'option_value'      => $product_option['option_value'],
						'required'          => $product_option['required']
				);
			}
		}
	
		return $product_option_data;
	}
	
	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();
			
		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->setting->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
	
		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			
			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->setting->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->setting->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");
				
			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
						'attribute_id' => $product_attribute['attribute_id'],
						'name'         => $product_attribute['name'],
						'text'         => $product_attribute['text']
				);
			}
				
			$product_attribute_group_data[] = array(
					'attribute_group_id' => $product_attribute_group['attribute_group_id'],
					'name'               => $product_attribute_group['name'],
					'attribute'          => $product_attribute_data
			);
		}
	
		return $product_attribute_group_data;
	}
	
	public function getProductRelated($product_id) {
		$product_data = array();

		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->setting->get('config_store_id') . "'");
	
		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}
	
		return $product_data;
	}
	
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}
	
	
}
?>