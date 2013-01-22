<?php
class Url extends system{
	public $url;
	private $ssl;
	private $rewrite = array();
	
	public function __construct($system) {
		$this->initialize($system);
		
		$url = $this->setting->get( 'config_url' );
		$ssl = $this->setting->get( 'config_use_ssl' ) ? $this->setting->get( 'config_ssl' ) : $this->setting->get ( 'config_url' );
		
		$this->url = $url;
		$this->ssl = $ssl;
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($page, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;	
		} else {
			$url = $this->ssl;	
		}
		
		$url .= 'index.php?page=' . $page;
			
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
}
?>