<?php   
class category_selector extends Module {
	public function process() {	 		
		
		
		$this->data['heading_title'] = $this->text('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
					
		$this->data['categories'] = array();
		
		$categories = $this->services->get_categories(0);
		
		foreach ($categories as $category) {
			$total = $this->services->get_total_products(array('filter_category_id'  => $category['category_id']));
		
			$children_data = array();
		
			$children = $this->services->get_categories($category['category_id']);
		
			foreach ($children as $child) {
				$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
				);
		
				$product_total = $this->services->get_total_products($data);
		
				$total += $product_total;
		
				$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'] . ($this->setting->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'        => $this->url->link('category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
				);
			}
		
			$this->data['categories'][] = array(
					'category_id' => $category['category_id'],
					'name'        => $category['name'] . ($this->setting->get('config_product_count') ? ' (' . $total . ')' : ''),
					'children'    => $children_data,
					'href'        => $this->url->link('category', 'path=' . $category['category_id'])
			);
		}
		
		
		
	} 	
}
?>