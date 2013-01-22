<?php   
class services{
	private $shop;
	public function __construct($controller) {
		$this->shop = $controller;
	}

	public function get_languages() {
		return $this->shop->model("shop_model@base")->getLanguages();
	}
	
	public function get_customer_by_id($customer_id) {
		return $this->shop->model('customer_model@account')->getCustomerById((int)$customer_id);
	}

	public function update_customer($customer_id, $cart, $wishlist,$remote_address) {
		return $this->shop->model('customer_model@account')->updateCustomer((int)$customer_id, $cart, $wishlist, $remote_address);
	}
		
	public function get_customer_ip($customer_id, $remote_address) {
		return $this->shop->model('customer_model@account')->getCustomerIp((int)$customer_id, $remote_address);
	}
	
	public function insert_customer_ip($customer_id, $remote_address) {
		return $this->shop->model('customer_model@account')->insertCustomerIp((int)$customer_id, $remote_address);		
	}
	
	public function update_customer_ip($customer_id, $remote_address) {
		return $this->controller->model('customer_model@account')->updateCustomerIp((int)$customer_id, $remote_address);
	}
	
	
	public function get_customer_by_email($email) {
		return $this->shop->model('customer_model@account')->getCustomerByEmail($email);		
	}
	public function get_customer_by_email_and_password($email, $password) {
		return $this->controller->model('customer_model@account')->getCustomerByEmailAndPassword($email,$password);
	}
	
	
	
	public function redeem($coupon_id, $order_id, $customer_id, $order_total_value) {
		return $this->shop->model('coupon_model@checkout')->redeem($coupon_id, $order_id, $customer_id, $order_total_value);
	}
	
	public function get_coupon($coupon_code) {
		return $this->shop->model('coupon_model@checkout')->getCoupon($coupon_code);
	}

	public function get_total($mode, $total_data, $total, $taxes) {
			return $this->shop->model('coupon_model@checkout')->getTotal($mode, $total_data, $total, $taxes);
	}
	
	public function get_voucher($voucher_code) {
		return $this->shop->model('voucher_model@checkout')->getVoucher($voucher_code);
	}
	
	
	public function get_customer_reward_sum($customer_id) {
		return $this->shop->model('customer_model@account')->getCustomerRewardSum((int)$customer_id);
	}
	
	public function get_customer_transactions_sum($customer_id) {
		return $this->shop->model('customer_model@account')->getCustomerTransactionsSum((int)$customer_id);
	}
	
	public function get_geo_zones() {
		return $this->shop->model('weight_model@shipping')->getGeoZones();
	}

	public function get_tax_by_address($tax_class_id,$customer_group_id, $country_id,$zone_id) {
		return $this->shop->model('tax_model@payment')->getTaxByAddress($tax_class_id,$customer_group_id, $country_id,$zone_id);
	}
	
	public function get_tax_by_rate($tax_rate_id) {
		return $this->shop->model('tax_model@payment')->getTaxByRate($tax_rate_id);
	}
	
	public function get_weight_classes() {
		return $this->shop->model('weight_model@shipping')->getWeightClasses();
	}
	
	public function get_cart_product($product_id) {
		return $this->shop->model('cart_model@checkout')->getCartProduct($product_id);		
	}

	public function get_cart_product_option($product_id, $product_option_id) {
		return $this->shop->model('cart_model@checkout')->getCartProduct($product_id,$product_option_id);
	}

	public function get_cart_product_option_value($product_option_id, $product_option_value_id) {
		return $this->shop->model('cart_model@checkout')->getCartProduct($product_option_id,$product_option_value_id);
	}

	public function get_cart_product_discount($product_id, $customer_group_id, $discount_quantity) {
		return $this->shop->model('cart_model@checkout')->getCartProductDiscount($product_id, $customer_group_id, $discount_quantity);
	}
	
	public function get_cart_product_special($product_id, $customer_group_id) {
		return $this->shop->model('cart_model@checkout')->getCartProductSpecial($product_id, $customer_group_id);
	}
	
	public function get_cart_product_reward($product_id, $customer_group_id) {
		return $this->shop->model('cart_model@checkout')->getCartProductReward($product_id, $customer_group_id);
	}
	
	public function get_cart_product_download($product_id) {
		return $this->shop->model('cart_model@checkout')->getCartProductDownload($product_id);
	}
	
	
	public function get_product_attributes($product_id) {
		return $this->shop->model('product@product')->getProductAttributes($product_id);
	}

	public function get_product_options($product_id) {
		return $this->shop->model('product@product')->getProductOptions($product_id);
	}
	
	public function get_product_related($product_id) {
		return $this->shop->model('product@product')->getProductRelated($product_id);
	}
	
	public function update_product_viewed($product_id) {
		return $this->shop->model('product@product')->updateViewed($product_id);
	}
	
	
	public function get_product_discounts($product_id) {
		return $this->shop->model('product@product')->getProductDiscounts($product_id);
	}
	
	public function get_product_images($product_id) {
		return $this->shop->model('product@product')->getProductImages($product_id);
	}
	
	public function get_total_reviews_by_product_id($product_id) {
		return $this->shop->model('product@product')->getTotalReviewsByProductId($product_id);
	}
	
	public function get_products() {
		return $this->shop->model('product@product')->getProducts();		
	}
	
	public function get_categories_by_parentid($category_id) {
		return $this->shop->model('category@base')->getCategoriesByParentId($category_id);		
	}
	
	public function get_total_products($data = array()) {
		return $this->shop->model('product@product')->getTotalProducts($data);
	}
	public function get_category($path_id) {
		return $this->shop->model('category@base')->getCategory($path_id);
	}
	
	public function get_manufacturer($manufacturer_id) {
		return $this->shop->model('category@base')->getManufacturer($manufacturer_id);
	}
	
	public function edit_password($email, $password) {
		return $this->shop->model('customer_model@account')->editPassword($email, $password);		
	}

	public function get_extensions($type) {
		return $this->shop->model('shop_model@base')->getExtensions($type);		
	}
	
	public function text($key, $path=NULL) {
		return $this->shop->language->text($key,$path);		
	}
	
	public function get_post_attribute($key) {
		if(isset($this->shop->request->post[$key])) return $this->shop->request->post[$key];
		return '';
	}

	public function get_get_attribute($key) {
		if(isset($this->shop->request->get[$key])) return $this->shop->request->get[$key];
		return '';
	}
	
	public function get_server_attribute($key) {
		return $this->shop->request->server[$key];
	}
	public function unset_session_attribute($key) {
		$this->shop->http_session[$key]=null;
	}
	
	public function get_session_attribute($key) {
		return $this->shop->http_session[$key];
	}
	public function set_session_attribute($key, $value) {
		$this->shop->http_session[$key] = $value;
	}
	public function get_customer_email() {
		return 	$this->shop->customer->getEmail();
	
	}
	
	public function is_set($key) {
		if(isset($key) && $key!='') return true;
		return false;
	}
	
	public function get_customer_group_info() {
		return $this->shop->model('customer_model@account')->getCustomerGroup($this->shop->customer->getCustomerGroupId());
	}
	public function get_countries() {
		return $this->shop->model('localization_model@system')->getCountries();		
	}
	
	public function get_customer_address_id() {
		return $this->shop->customer->getAddressId();		
	}
	
	public function set_currency($currency_code) {
		$this->shop->currency->set($currency_code);		
	}
	
	public function insert_customer_address() {
		$this->shop->model('address_model@account')->addAddress($this->shop->customer->getId(), $this->shop->request->post);		
	}
	public function update_customer_address($address_id) {
		$this->shop->model('address_model@account')->editAddress($this->shop->customer->getId(), $address_id, $this->shop->request->post);
	}
	
	public function get_country($country_id) {
		return $this->shop->model('localization_model@system')->getCountry($country_id);		
	}
	
	public function redirect($link) {		
		$this->shop->helper->redirect($link);		
	} 
	
	public function get_customer_address($address_id) {
		return $this->shop->model ( 'address_model@account' )->getAddress ( $this->shop->customer->getId (), $address_id );		
	}
	
	public function get_zones($country_id) {
		return $this->shop->model ( 'localization_model@system' )->getZonesByCountryId ($country_id );		
	}
	
	public function isset_get_attribute($key) {
		return isset($this->shop->request->get[$key]);
	}

	public function isset_post_attribute($key) {
		return isset($this->shop->request->post[$key]);
	}

	public function isset_session_attribute($key) {
		return isset($this->shop->session[$key]);
	}

	public function isset_server_attribute($key) {
		return isset($this->shop->server[$key]);
	}

	public function empty_get_attribute($key) {
		return empty($this->shop->request->get[$key]);
	}
	
	public function empty_post_attribute($key) {
		return empty($this->shop->request->post[$key]);
	}
	
	public function empty_session_attribute($key) {
		return empty($this->shop->session[$key]);
	}
	
	public function get_customer_addresses() {
		return $this->shop->model('address_model@account')->getAddresses($this->shop->customer->getId());
	}
	
	public function delete_customer_address($address_id) {
		return $this->shop->model('address_model@account')->deleteAddress($this->shop->customer->getId(), $address_id);
		
	}
	
	public function get_product($product_id) {
		return 	$this->shop->model('product@product')->getProduct($product_id);
			
	}
	
	public function resize($image, $width, $height) {
		return $this->shop->model('product@product')->resize($image, $width, $height);	
	}
		
	public function get_currencies() {
		return $this->shop->model('localization_model@system')->getCurrencies();		
	}
	
	public function get_currencies_all() {
		return $this->shop->model('localization_model@system')->getAllCurrencies();		
	}
	
	public function get_currency_code() {
		return $this->shop->currency->getCode();
	}
	
	public function get_customer() {
		return $this->shop->model('customer_model@account')->getCustomer($this->shop->customer->getId());		
	}
	
	public function edit_customer() {
		$this->shop->model('customer_model@account')->editCustomer($this->shop->request->post);		
	}
	public function get_total_customers_by_email($email) {
		return $this->shop->model('customer_model@account')->getTotalCustomersByEmail($email);		
	}
	
	public function get_settings() {
		return $this->shop->model('shop_model@base')->getSettings();
	}
	
	public function get_module($module_path, $module_code, $action=NULL) {
		return $this->shop->get_module($module_path, $module_code, $action);		
	}
	public function get_customer_default_address() {
		return 	$this->shop->model('address_model@account')->getAddress($this->shop->customer->getId(), $this->shop->customer->getAddressId());	
	}
	
	public function get_customer_by_token($token) {
		return $this->shop->model('customer@account')->getCustomerByToken($token);
	}
	
	public function login($email,$password, $override=false) {
		return $this->shop->customer->login($email, $password, $override);
	}
	public function edit_customer_password($email, $password) {
		$this->shop->model('customer_model@account')->editPassword($email, $password);
		
	}
	
	public function add_customer($post) {
		$this->shop->model('customer_model@account')->addCustomer($post);
		
	}
	
	public function get_customer_groups() {
		return $this->shop->model('customer_model@account')->getCustomerGroups($this->get_setting('config_language_id'));				
	}
	
	public function get_information($config_account_id) {
		return $this->shop->model('information@base')->getInformation($config_account_id);		
	}
	
	public function get_informations() {
		return $this->shop->model('information@base')->getInformations();		
	}
	public function get_customer_group($customer_group_id) {
		return $this->shop->model('customer_model@account')->getCustomerGroup($customer_group_id);		
	}
	
	public function get_categories($parentid=0) {
		return $this->shop->model('category@base')->getCategories($parentid);		
	}
	
	public function get_total_addresses($customer_id) {
		return $this->shop->model('address_model@account')->getTotalAddresses($customer_id);
	}
	
	public function get_banner($banner_id) {
		return $this->shop->model("banner@base")->getBanner($banner_id);		
	}
	
}
?>