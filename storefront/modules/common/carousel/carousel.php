<?php   
class carousel extends Module {
	protected $banner_id=8;
	protected $image_width=80;
	protected $image_height=80;
	
	public function process() {	 		
		
		
		$this->document->addScript(MODULE_JS_DIR.'jquery/jquery.jcarousel.min.js');		
		$this->document->addStyle(MODULE_CSS_DIR.'carousel.css');
		
		$this->data['limit'] = $this->setting->get('limit');
		$this->data['scroll'] = $this->setting->get('scroll');
		
		$this->data['banners'] = array();
		
		$results = $this->services->get_banner($this->banner_id);
		
		foreach ($results as $result) {
			if (file_exists(IMAGE_DIR . $result['image'])) {
				$this->data['banners'][] = array(
						'title' => $result['title'],
						'link'  => $result['link'],
						'image' => $this->services->resize($result['image'], $this->image_width, $this->image_height)
				);
			}
		}
				
	} 	
}
?>