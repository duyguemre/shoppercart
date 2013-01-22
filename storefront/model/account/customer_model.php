<?php
class customer_model extends Model {
	public function getCustomerById($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $customer_id . "' AND status = '1'");
		return $query;
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $customer_id . "' AND status = '1'");
		return $query->row;
	}
	
	public function updateCustomer($customer_id, $cart, $wishlist, $REMOTE_ADDR) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($cart) ? serialize($cart) : '') . "', wishlist = '" . $this->db->escape(isset($wishlist) ? serialize($wishlist) : '') . "', ip = '" . $this->db->escape($REMOTE_ADDR) . "' WHERE customer_id = '" . $customer_id . "'");
	}
	
	public function getCustomerIp($customer_id, $REMOTE_ADDR) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . $customer_id . "' AND ip = '" . $this->db->escape($REMOTE_ADDR) . "'");
		return $query;
	}
	
	
	public function insertCustomerIp($customer_id, $REMOTE_ADDR) {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . $customer_id . "', ip = '" . $this->db->escape($REMOTE_ADDR) . "', date_added = NOW()");
		return $query;
	}
	
	
	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer where LOWER(email) = '" . $this->db->escape(strtolower($email)) . "' AND status = '1'");
		return $query->row;
	}
	
	
	public function getCustomerByEmailAndPassword($email, $password) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		return $query;
	}
	
	
	
	public function updateCustomerIp($customer_id, $REMOTE_ADDR) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($REMOTE_ADDR) . "' WHERE customer_id = '" . $customer_id . "'");
		return $query;
	}	
	
	public function getCustomerTransactionsSum($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . $customer_id . "'");
		return $query;
	}
	
	public function getCustomerRewardSum($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . $customer_id . "'");
		return $query;
	}	
	
	
	
	
	
	
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->setting->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->setting->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->setting->get('config_customer_group_id');
		}
	
		$customer_group_info = $this->services->get_customer_group($customer_group_id);
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->setting->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
		 
		$customer_id = $this->db->getLastId();
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
	
		$address_id = $this->db->getLastId();
	
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
	
		$subject = sprintf($this->services->text('text_subject','account_login@account'), $this->setting->get('config_name'));
	
		$message = sprintf($this->services->text('text_welcome','account_login@account'), $this->setting->get('config_name')) . "\n\n";
	
		if (!$customer_group_info['approval']) {
			//$message .= $this->services->text('text_login','login@account') . "\n";
		} else {
			$message .= $this->services->text('text_approval','login@account') . "\n";
		}
	
		$message .= $this->url->link('account', 'account_management=login', 'SSL') . "\n\n";
		$message .= $this->services->text('text_services','login@account') . "\n\n";
		$message .= $this->services->text('text_thanks','login@account') . "\n";
		$message .= $this->setting->get('config_name');
	
		$mail = new Mail();
		$mail->protocol = $this->setting->get('config_mail_protocol');
		$mail->parameter = $this->setting->get('config_mail_parameter');
		$mail->hostname = $this->setting->get('config_smtp_host');
		$mail->username = $this->setting->get('config_smtp_username');
		$mail->password = $this->setting->get('config_smtp_password');
		$mail->port = $this->setting->get('config_smtp_port');
		$mail->timeout = $this->setting->get('config_smtp_timeout');
		$mail->setTo($data['email']);
		$mail->setFrom($this->setting->get('config_email'));
		$mail->setSender($this->setting->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	
		// Send to main admin email if new account email is enabled
		if ($this->setting->get('config_account_mail')) {
			$mail->setTo($this->setting->get('config_email'));
			$mail->send();
				
			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->setting->get('config_alert_emails'));
				
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
	
	public function editCustomer($data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$SHOP->customer->getId() . "'");
	}
	
	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE email = '" . $this->db->escape($email) . "'");
	}
	
	public function editNewsletter($newsletter) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$SHOP->customer->getId() . "'");
	}
		
	
	
	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
	
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");
	
		return $query->row;
	}
	
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";
	
		$implode = array();
	
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
	
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}
	
		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}
	
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}
	
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}
			
		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}
	
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
	
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
	
		$sort_data = array(
				'name',
				'c.email',
				'customer_group',
				'c.status',
				'c.ip',
				'c.date_added'
		);
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
	
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
				
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
	
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "'");
	
		return $query->row['total'];
	}
	
	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->rows;
	}
	
	public function isBlacklisted($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip_blacklist` WHERE ip = '" . $this->db->escape($ip) . "'");
	
		return $query->num_rows;
	}	
	
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	
		return $query->row;
	}
	
	public function getCustomerGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->setting->get('config_language_id') . "' ORDER BY cg.sort_order ASC, cgd.name ASC");
		return $query->rows;
	}	
	
}
?>