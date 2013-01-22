<?php   
class category_product_list extends Module {
	public function process() {	 		
		
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
			
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->setting->get('config_catalog_limit');
		}
						
		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}
					
				$category_info = $this->services->get_category($path_id);
		
			}
		
			$category_id = (int)array_pop($parts);
		} else {
			$category_id = 0;
		}
		
		$category_info = $this->services->get_category($category_id);
		
		if ($category_info) {
			$this->document->setTitle($category_info['name']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
				
			$this->data['heading_title'] = $category_info['name'];
				
			$this->data['text_refine'] = $this->text('text_refine', "category_product_list@category");
			$this->data['text_empty'] = $this->text('text_empty', "category_product_list@category");
			$this->data['text_quantity'] = $this->text('text_quantity', "category_product_list@category");
			$this->data['text_manufacturer'] = $this->text('text_manufacturer', "category_product_list@category");
			$this->data['text_model'] = $this->text('text_model', "category_product_list@category");
			$this->data['text_price'] = $this->text('text_price', "category_product_list@category");
			$this->data['text_tax'] = $this->text('text_tax', "category_product_list@category");
			$this->data['text_points'] = $this->text('text_points', "category_product_list@category");
			$this->data['text_compare'] = sprintf($this->text('text_compare', "category_product_list@category"), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->text('text_display', "category_product_list@category");
			$this->data['text_list'] = $this->text('text_list', "category_product_list@category");
			$this->data['text_grid'] = $this->text('text_grid', "category_product_list@category");
			$this->data['text_sort'] = $this->text('text_sort', "category_product_list@category");
			$this->data['text_limit'] = $this->text('text_limit', "category_product_list@category");
				
			$this->data['button_cart'] = $this->text('button_cart', "common@basic");
			$this->data['button_wishlist'] = $this->text('button_wishlist', "common@basic");
			$this->data['button_compare'] = $this->text('button_compare', "common@basic");
			$this->data['button_continue'] = $this->text('button_continue', "common@basic");
				
			if ($category_info['image']) {
				$this->data['thumb'] = $this->services->resize($category_info['image'], $this->setting->get('config_image_category_width'), $this->setting->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}
				
			$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['compare'] = $this->url->link('product/compare');
				
			$url = '';
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
		
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
		
			$this->data['categories'] = array();
				
			$results = $this->services->get_categories($category_id);
				
			foreach ($results as $result) {
				$data = array(
						'filter_category_id'  => $result['category_id'],
						'filter_sub_category' => true
				);
		
				$product_total = $this->services->get_total_products($data);
		
				$this->data['categories'][] = array(
						'name'  => $result['name'] . ($this->setting->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}
				
			$this->data['products'] = array();
				
			$data = array(
					'filter_category_id' => $category_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
			);
				
			$product_total = $this->services->get_total_products($data);
				
			$results = $this->services->get_products($data);
				
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->services->resize($result['image'], $this->setting->get('config_image_product_width'), $this->setting->get('config_image_product_height'));
				} else {
					$image = false;
				}
		
				//if (($this->setting->get('config_customer_price') && $this->customer->isLogged()) || !$this->setting->get('config_customer_price')) {
				if (!$this->setting->get('config_customer_price')) {
					$price = 10;
					//$this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$price = false;
				}
		
				if ((float)$result['special']) {
					$special = 9;
					//$this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->setting->get('config_tax')));
				} else {
					$special = false;
				}
		
				if ($this->setting->get('config_tax')) {
					$tax = 2;
					//$this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}
		
				if ($this->setting->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
		
				$this->data['products'][] = array(
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $result['rating'],
						'reviews'     => sprintf($this->text('text_reviews','common@basic'), (int)$result['reviews']),
						'href'        => $this->url->link('product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
				);
			}
				
			$url = '';
		
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
				
			$this->data['sorts'] = array();
				
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_default', "common@basic"),
					'value' => 'p.sort_order-ASC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);
				
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_name_asc','common@basic'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);
		
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_name_desc','common@basic'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);
		
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_price_asc','common@basic'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);
		
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_price_desc','common@basic'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);
				
			if ($this->setting->get('config_review_status')) {
				$this->data['sorts'][] = array(
						'text'  => $this->text('text_rating_desc','common@basic'),
						'value' => 'rating-DESC',
						'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);
		
				$this->data['sorts'][] = array(
						'text'  => $this->text('text_rating_asc','common@basic'),
						'value' => 'rating-ASC',
						'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}
				
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_model_asc','common@basic'),
					'value' => 'p.model-ASC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);
		
			$this->data['sorts'][] = array(
					'text'  => $this->text('text_model_desc','common@basic'),
					'value' => 'p.model-DESC',
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);
				
			$url = '';
		
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
		
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$this->data['limits'] = array();
				
			$this->data['limits'][] = array(
					'text'  => $this->setting->get('config_catalog_limit'),
					'value' => $this->setting->get('config_catalog_limit'),
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $this->setting->get('config_catalog_limit'))
			);
		
			$this->data['limits'][] = array(
					'text'  => 25,
					'value' => 25,
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&limit=25')
			);
				
			$this->data['limits'][] = array(
					'text'  => 50,
					'value' => 50,
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&limit=50')
			);
		
			$this->data['limits'][] = array(
					'text'  => 75,
					'value' => 75,
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&limit=75')
			);
				
			$this->data['limits'][] = array(
					'text'  => 100,
					'value' => 100,
					'href'  => $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&limit=100')
			);
		
			$url = '';
		
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
		
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
		
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
				
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->text('text_pagination','common@basic');
			$pagination->url = $this->url->link('category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
		
			$this->data['pagination'] = $pagination->render();
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');
		
		} else {
			$this->data['show'] = 0;
			
			$url = '';
				
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
		
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
		
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
		
		
			$this->document->setTitle($this->text('text_error', "category_product_list@category"));
		
			$this->data['heading_title'] = $this->text('text_error', "category_product_list@category");
		
			$this->data['text_error'] = $this->text('text_error', "category_product_list@category");
		
			$this->data['button_continue'] = $this->text('button_continue', "common@basic");
		
			$this->data['continue'] = $this->url->link('home', "common@basic");
				
		} 	
	} 	
}
?>