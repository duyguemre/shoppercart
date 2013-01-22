,<?php
class slideshow extends Module {
	protected $id=0;
	protected $banner_id = 7;
	protected $image_width = 980;
	protected $image_height = 280;
	public function process() {
		$this->document->addScript ( MODULE_JS_DIR . 'jquery/nivo-slider/jquery.nivo.slider.pack.js' );
		$this->document->addStyle ( MODULE_CSS_DIR . 'slideshow.css' );
		
		$this->data ['id'] = $this->id;
		$this->data ['width'] = $this->image_width;
		$this->data['height'] = $this->image_height;
		
		$this->data ['banners'] = array ();
		
		$results = $this->services->get_banner($this->banner_id);
		
		foreach ( $results as $result ) {
			if (file_exists ( IMAGE_DIR . $result ['image'] )) {
				$this->data ['banners'] [] = array (
						'title' => $result ['title'],
						'link' => $result ['link'],
						'image' => $this->services->resize ( $result ['image'], $this->image_width, $this->image_height ) 
				);				
			}
		}
	}
}
?>