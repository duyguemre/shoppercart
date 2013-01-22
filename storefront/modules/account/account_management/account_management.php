<?php
class account_management extends Module {
	private $breadcrumb;
	 	
	protected function check() {
		
		if ($this->customer->isLogged()) {				
			if (isset($this->request->get [get_class($this)]) && $this->request->get [get_class($this)]=='login' ) {
				$this->session->data['redirect'] = $this->url->link ( 'account', '', 'SSL' );
				$this->services->redirect( $this->url->link ( 'account', '', 'SSL' ) );
			}
		} else {
			if(isset($this->request->get [get_class($this)])) {
				$dispatch = $this->request->get [get_class($this)];
				if ($dispatch!='login' && $dispatch!='forgotten' && $dispatch!='register') {
					$this->session->data['redirect'] = $this->url->link('account', '', 'SSL');
					$this->services->redirect($this->url->link('account', get_class($this) .  '=login', 'SSL'));
				}				
			}
		}		
	}
	
	protected function process() {
		$this->check();
		
		$this->breadcrumb = $this->services->get_module('category', 'breadcrumb');				
		$this->add_breadcrumb('account');
		$this->data['heading_title'] = $this->text('heading_title');
		$this->data['content'] = '';		
		if (isset ($this->request->get [get_class($this)]) && $this->request->get [get_class($this)]!='account') {
			$service_name = str_replace('/', '_', $this->request->get [get_class($this)]);
			$this->data['content'] = $this->$service_name();
		}
		
		if (isset($SHOP->session['success'])) {
			$this->data['success'] = $SHOP->session['success'];		
			unset($SHOP->session['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['account'] = $this->account();
		$this->data['breadcrumbs'] = $this->breadcrumb->render();		
	}
	
	private function account() {
		
		$account = $this->services->get_module('account', 'account');

/*		
		$fields  = get_object_vars($account);
		foreach($fields as $key=>$val) {
			echo $key;
			$this->$key = "HABAAAA";
		}
		
		$param = array(
				'$insert_action_link'=>'insert',
				'$back_action_link'=>      'back',
				'$update_action_link'=>'upate'			
				);
		$string =file_get_contents("D:/Nameless/Zend/Apache2/htdocs/shoppercart/modules/account/address_form/controller/address_form.php");
		$pattern = '/public\n*\s*\n*\$(\w+)\n*\s*\n*[A-Za-z=\s\n"]*;/i';
		$replacement = 'public \$${1}=\'doit\';';
		echo preg_replace($pattern, $replacement, $string);
*/		
		
		
		$this->document->setTitle($this->text('heading_title'));		
		return $account->render();		
	}
	
	private function login() {
		$this->add_breadcrumb('login');		
		$login_form = $this->services->get_module('account', 'account_login');
		$this->document->setTitle($this->text('heading_title'));		
		$login_form->register_action_link=$this->url->link('account', 'account_management=register', 'SSL');
		$login_form->forgotten_action_link=$this->url->link('account', 'account_management=forgotten', 'SSL');
		$login_form->login_action_link=$this->url->link('account', 'account_management=login', 'SSL');		
		$login_form->back_action_link=$this->url->link('account', '', 'SSL');		
		return $login_form->render();		
	}
	
	private function register() {
		$this->add_breadcrumb('register');
		$register = $this->services->get_module('account', 'register');
		$this->document->setTitle($this->text('heading_title'));
		$register->register_action_link=$this->url->link('account', 'account_management=register', 'SSL');
		$register->success_action_link=$this->url->link('account', '', 'SSL');
		$register->login_action_link=$this->url->link('account', 'account_management=login', 'SSL');
		return $register->render();
	}
	

	private function forgotten() {
		$this->add_breadcrumb('forgotten');
		$forgotten = $this->services->get_module('account', 'forgotten');
		$this->document->setTitle($this->text('heading_title'));
		$forgotten->forgotten_action_link=$this->url->link('account', 'account_management=forgotten', 'SSL');
		$forgotten->back_action_link=$this->url->link('account', 'account_management=login', 'SSL');
		return $forgotten->render();
	}
	
	private function wishlist() {
		
		$this->add_breadcrumb('wishlist');
		$wishlist = $this->services->get_module('account', 'wishlist');
		$this->document->setTitle($this->text('heading_title'));
		$wishlist->remove_action_link=$this->url->link('account', 'account_management=wishlist', 'SSL');
		$wishlist->back_action_link=$this->url->link('account', '', 'SSL');		
		return $wishlist->render();
	}
	
	private function password() {				
		$this->add_breadcrumb('password');
		$change_password = $this->services->get_module('account', 'change_password', 'change');
		$change_password->change_action_link=$this->url->link('account', 'account_management=password', 'SSL');
		$change_password->back_action_link=$this->url->link('account', '', 'SSL');
		$this->document->setTitle($this->text('heading_title'));			
		return $change_password->render();		
	}
	
	private function edit() {		
		$this->add_breadcrumb('edit');
		$account_edit = $this->services->get_module('account', 'account_edit');
		$account_edit->update_action_link= $this->url->link('account', 'account_management=edit', 'SSL');
		$account_edit->back_action_link=$this->url->link('account', '', 'SSL');
		$this->document->setTitle($this->text('heading_title','edit@account'));		
		return $account_edit->render();		
	}
	
	private function address() {		
		$this->add_breadcrumb('address');
		$this->document->setTitle($this->text('heading_title'));
		$address_list = $this->services->get_module('account', 'address_list');		
		$address_list->delete_action_link=$this->url->link('account', 'account_management=address/delete', 'SSL');
		$address_list->update_action_link=$this->url->link('account', 'account_management=address/update', 'SSL');
		$address_list->insert_action_link=$this->url->link('account', 'account_management=address/insert', 'SSL');		
		$address_list->back_action_link=$this->url->link('account', '', 'SSL');		
		return $address_list->render();		
	}
	
	private function address_insert() {				
		$this->add_breadcrumb('address');
		$this->add_breadcrumb('address_insert');
		$address_form = $this->services->get_module('account', 'address_manipulate', 'insert');	
		$address_form->update_action_link= $this->url->link('account', 'account_management=address/update', 'SSL');
		$address_form->insert_action_link=$this->url->link('account', 'account_management=address/insert', 'SSL');
		$address_form->back_action_link= $this->url->link('account', 'account_management=address', 'SSL');		
		$this->document->setTitle($this->text('heading_title'));
		return $address_form->render();		
	}
	
	
	private function address_update() {
		
		$this->add_breadcrumb('address');
		$this->add_breadcrumb('address_update');		
		$address_form = $this->services->get_module('account', 'address_manipulate', 'update');
		$address_form->update_action_link= $this->url->link('account', 'account_management=address/update', 'SSL');
		$address_form->insert_action_link=$this->url->link('account', 'account_management=address/insert', 'SSL');
		$address_form->back_action_link= $this->url->link('account', 'account_management=address', 'SSL');		
		$this->document->setTitle($this->text('heading_title'));	
		return $address_form->render();		
	}
	
	
	private function address_delete() {
		
		$this->add_breadcrumb('address');
		$address_list = $this->services->get_module('account', 'address_list', 'delete');
		$address_list->delete_action_link= $this->url->link('account', 'account_management=address/delete', 'SSL');
		$address_list->update_action_link= $this->url->link('account', 'account_management=address/update', 'SSL');
		$address_list->insert_action_link=$this->url->link('account', 'account_management=address/insert', 'SSL');
		$address_list->back_action_link= $this->url->link('account', 'account_management=address', 'SSL');		
		$this->document->setTitle($this->text('heading_title'));			
		return $address_list->render();		
	}
		
	private function add_breadcrumb($key) {
		
		$this->breadcrumb->add($this->text('text_'.$key . '_breadcrumb','account_management@account'),
							   $this->url->link($this->page, 'account_management=' . $key),
							   $this->text('text_separator'));
	}			
}
?>