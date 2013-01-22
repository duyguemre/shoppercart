<?php
abstract class system {
	public $session;
	public $customer;
	public $cart;
	public $tax;
	public $weight;
	public $currency;
	public $services;
	public $request;
	public $response;
	public $document;
	public $setting;
	public $cache;
	public $helper;
	public $log;
	public $url;
	public $language;
	public $registry;
	public $protocol;
	public $page;
	public $db;
	public $duygu;
	public function __construct() {
	}
	private function start_session() {
		if (! session_id ()) {
			ini_set ( 'session.use_cookies', 'On' );
			ini_set ( 'session.use_trans_sid', 'Off' );
			session_set_cookie_params ( 0, '/' );
			session_start ();
		}
	}
	private function load_registry() {
		$this->registry = new Registry ();
		$this->registry->set_ref ( 'session', $this->session );
		$this->registry->set ( 'customer', $this->customer );
		$this->registry->set ( 'cart', $this->cart );
		$this->registry->set ( 'tax', $this->tax );
		$this->registry->set ( 'weight', $this->weight );
		$this->registry->set ( 'currency', $this->currency );
		$this->registry->set ( 'services', $this->services );
		$this->registry->set ( 'request', $this->request );
		$this->registry->set ( 'document', $this->document );
		$this->registry->set ( 'response', $this->response );
		$this->registry->set ( 'language', $this->language );
		$this->registry->set ( 'duygu', $this->duygu );
	}
	protected function initialize($system = NULL) {
		if ($system == NULL) {
			date_default_timezone_set ( DEFAULT_TIMEZONE );
			$this->start_session ();
			
			$this->db = new DB ( DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE );
			
			$this->duygu = new duygu ();
			
			$this->request = new request ();
			$this->response = new response ();
			$this->session = & $_SESSION;
			$this->cache = new Cache ();
			$this->document = new Document ();
			$this->helper = new Helper ();
			$this->log = new Log ();
			
			$this->services = new Services ( $this );
			$this->setting = new Setting ( $this );
			$this->language = new language ( $this );
			$this->url = new Url ( $this );
			$this->customer = new Customer ( $this );
			$this->currency = new Currency ( $this );
			$this->tax = new tax ( $this );
			$this->weight = new weight ( $this );
			$this->cart = new Cart ( $this );
			
			$this->load_registry ();
		} else {
			$this->request = $system->request;
			$this->response = $system->response;
			$this->session = & $system->session;
			$this->log = $system->log;
			$this->setting = $system->setting;
			$this->language = $system->language;
			$this->currency = $system->currency;
			$this->customer = $system->customer;
			$this->url = $system->url;
			$this->weight = $system->weight;
			$this->tax = $system->tax;
			$this->cache = $system->cache;
			$this->services = $system->services;
			$this->document = $system->document;
			$this->helper = $system->helper;
			$this->registry = $system->registry;
			$this->cart = $system->cart;
			$this->page = $system->page;
			$this->db = $system->db;
		}
	}
}
?>