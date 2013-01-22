<?php 
class address_list extends module {	
	public $new_address_page;
	public $delete_page;
	public $update_page;
	
  	protected function process() {
  		
    	$this->data['heading_title'] = $this->text('heading_title','address@account');
    	$this->data['text_address_book'] = $this->text('text_address_book','address@account');   
    	$this->data['button_new_address'] = $this->text('button_new_address','common@basic');
    	$this->data['button_edit'] = $this->text('button_edit','common@basic');
    	$this->data['button_delete'] = $this->text('button_delete','common@basic');

		if (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
				
    	$this->data['addresses'] = array();		
		$results = $this->services->get_customer_addresses();

    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
	
			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']  
			);

      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => $this->url->link($this->update_page, '&address_id=' . $result['address_id']),
				'delete'     => $this->url->link($this->delete_page, 'address_id=' . $result['address_id'])
      		);
    	}

    	$this->data['insert'] = $this->url->link($this->new_address_page);
								
		$this->data = $this->data;
  	}
  	
  	 
}
?>