<?php 
class breadcrumb extends module{ 
	public function process() {				
	  	if(!isset($this->data['breadcrumbs'])) {
  			$this->data['breadcrumbs'] = array();
  			$this->data['breadcrumbs'][] = array(
  					'text'      => $this->text('text_home'),
  					'href'      => $this->url->link('home'),
  					'separator' => false
  			);  				
  		}
  		
  		if (isset($this->request->get['path'])) {
  			$path = '';  		
  			foreach (explode('_', $this->request->get['path']) as $path_id) {
  				if (!$path) {
  					$path = $path_id;
  				} else {
  					$path .= '_' . $path_id;
  				}
  		
  				$category_info = $this->services->get_category($path_id);
  		
  				if ($category_info) {
  					$this->add(
  							$category_info['name'],
  							$this->url->link('product/category', 'path=' . $path),
  							$this->text('text_separator')
  					);
  				}
  			}
  		}
  		
  		
  		if (isset($this->request->get['manufacturer_id'])) {
  			$this->add(
  					$this->language->get('text_brand'),
  					$this->url->link('product/manufacturer'),
  					$this->text('text_separator')
  			);
  		
  			$manufacturer_info = $this->services->get_manufacturer($this->request->get['manufacturer_id']);
  		
  			if ($manufacturer_info) {
  				$this->add(
  						$manufacturer_info['name'],
  						$this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']),
  						$this->text('text_separator')
  				);
  			}
  		}  		
  		
  		if(isset($this->request->get['product_id'])) {
  			$product_info = $this->services->get_product($this->request->get['product_id']);
  			$this->add(
  					$product_info['name'],
  					'',
  					$this->text('text_separator')
  			);
  				
  		}
  		if (isset($this->request->get['path']) || isset($this->request->get['manufacturer_id'])) {
  			return;
  		}
  		  		
  		$tokens = explode('/', $this->page);
  		if(isset($this->request->get[end($tokens)])) $dispatch = $this->request->get[end($tokens)];
  		foreach ($tokens as $token) {
  			$temp = explode($token, $this->page);
  			if(end($tokens)== $token && isset($dispatch)) {
  				$text = $this->text($this->page . '->' .  $dispatch);
  			} else {
  				$text = $this->text($temp[0] . $token);
  			}
  			$this->add($text, $this->url->link($temp[0] . $token), $this->text('text_separator'));
  		}
  		
	}
  	
  	public function add($text, $href, $separator) {
  		$this->data['breadcrumbs'][] = array(
  				'text'      => $text,
  				'href'      => $href,
  				'separator' => $separator
  		);  		
  	}
}
?>