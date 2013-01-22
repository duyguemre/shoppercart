<?php
class Customer extends system{
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;
	
  	public function __construct($system) {
  		$this->initialize($system);
  		
  		if (isset($this->session['customer_id'])) { 
			
  			$customer_query = $this->services->get_customer_by_id($this->session['customer_id']);
			  				
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
				
				$http_session_cart = '';
				if(isset($this->session['cart'])) $http_session_cart = $this->session['cart'];
				
				$this->services->update_customer((int)$this->customer_id, $http_session_cart, isset($this->session['wishlist'])?$this->session['wishlist']:null, $this->request->server['REMOTE_ADDR']);

			

				$query = $this->services->get_customer_ip((int)$this->session['customer_id'], $this->request->server['REMOTE_ADDR']);
				
				if (!$query->num_rows) {
					$this->services->insert_customer_ip((int)(int)$this->session['customer_id'], $this->request->server['REMOTE_ADDR']);
				}
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password, $override = false) {
		if ($override) {
			$customer_query = $this->services->get_customer_by_email($email);
		} else {
			$customer_query = $this->services->get_customer_by_email_and_password($email, $password);
		}
		
		if ($customer_query->num_rows) {
			$this->session['customer_id'] = $customer_query->row['customer_id'];	
				
			if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
				$cart = unserialize($customer_query->row['cart']);
				if(!empty($cart)) {
					foreach ($cart as $key => $value) {
						if (!array_key_exists($key, $this->session['cart'])) {
							$this->session['cart'][$key] = $value;
						} else {
							$this->session['cart'][$key] += $value;
						}
					}						
				}
			}

			if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
				if (!isset($this->session['wishlist'])) {
					$this->session['wishlist'] = array();
				}
								
				$wishlist = unserialize($customer_query->row['wishlist']);
			
				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session['wishlist'])) {
						$this->session['wishlist'][] = $product_id;
					}
				}			
			}
			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id = $customer_query->row['address_id'];
          	
			$this->services->update_customer_ip((int)$this->session['customer_id'], $this->request->server['REMOTE_ADDR']);
	  		return true;
    	} else {
      		return false;
    	}
  	}
  	
	public function logout() {
		
		$this->services->update_customer((int)$this->customer_id, $this->session['cart'], $this->session['wishlist'], $this->request->server['REMOTE_ADDR']);		
		unset($this->session['customer_id']);
		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}

  	public function getId() {
    	return $this->customer_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
  
  	public function getFax() {
		return $this->fax;
  	}
	
  	public function getNewsletter() {
		return $this->newsletter;	
  	}

  	public function getCustomerGroupId() {
		return $this->customer_group_id;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
	
  	public function getBalance() {
  		$query = $this->services->get_customer_transactions_sum($this->customer_id);
  		return $query->row['total'];
  	}	
		
  	public function getRewardPoints() {
		$query = $this->services->get_customer_reward_sum($this->customer_id);
		return $query->row['total'];	
  	}	
}
?>