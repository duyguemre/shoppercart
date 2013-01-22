<?php   
class default_module extends module {	
	private $message;
	public function process() {	 		
		$this->data['message'] = $this->message . '';
	} 	
	public function set_message($message) {
		$this->message = $message;
	}
}
?>