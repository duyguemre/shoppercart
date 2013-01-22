<?php   
class featured extends Module {
	public function process() {	  
		
		
		$products = explode(',', $this->setting->get('featured_product'));		
		$products = array_slice($products, 0, 5);

		$this->cache->set("duygu", "emre");	
				

		foreach ($products as $product_id) {
			$product_info = $SHOP->model("product@product")->getProduct($product_id);
						
			$info = $product_id. $product_info. $this->setting->get('image_width');
			
      		$this->data['heading_title'] = "Featured";
			$this->data['button_cart'] = "Add to Cart";
			if ($product_info) {
				if ($product_info['image']) {
					$image = $SHOP->product_product->resize($product_info['image'], 80, 80);
				} else {
					$image = false;
				}
		
				$price = false;		
				$special = false;		
				$rating = false;
					 
				$this->data['products'][] = array(
						'product_id' => $product_info['product_id'],
						'thumb'   	 => $image,
						'name'    	 => $product_info['name'],
						'price'   	 => $price,
						'special' 	 => $special,
						'rating'     => $rating,
						'reviews'    => sprintf('Review', (int)$product_info['reviews']),
						'href'    	 => '34343',
				);
			}
		}
	} 	
}
?>