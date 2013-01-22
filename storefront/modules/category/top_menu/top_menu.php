<?php
class top_menu extends Module {
	public function process() {
		$categories = $this->services->get_categories ( 0 );
		
		foreach ( $categories as $category ) {
			if ($category ['top']) {
				$children_data = array ();				
				$children = $this->services->get_categories ( $category ['category_id'] );				
				foreach ( $children as $child ) {
					$data = array (
							'filter_category_id' => $child ['category_id'],
							'filter_sub_category' => true 
					);
					
					$product_total = 5;
					
					$children_data [] = array (
							'name' => $child ['name'] . ($this->setting->get ( 'config_product_count' ) ? ' (' . $product_total . ')' : ''),
							'href' => $this->url->link ( 'category', 'path=' . $category ['category_id'] . '_' . $child ['category_id'] ) 
					);
				}
				
				// Level 1
				$this->data ['categories'] [] = array (
						'name' => $category ['name'],
						'children' => $children_data,
						'column' => $category ['column'] ? $category ['column'] : 1,
						'href' => $this->url->link ( 'category', 'path=' . $category ['category_id'] ) 
				);
			}
		}
	}
}
?>