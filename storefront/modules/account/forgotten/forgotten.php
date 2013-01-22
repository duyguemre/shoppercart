<?php
class forgotten extends module {
	public function process() {
		$this->document->setTitle($this->text('heading_title'));		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);			
			$this->service->edit_customer_password($this->request->post['email'], $password);
			
			$subject = sprintf($this->text('text_subject'), $this->setting->get('config_name'));
			
			$message  = sprintf($this->text('text_greeting'), $this->setting->get('config_name')) . "\n\n";
			$message .= $this->text('text_password') . "\n\n";
			$message .= $password;

			$mail = new Mail();
			$mail->protocol = $this->setting->get('config_mail_protocol');
			$mail->parameter = $this->setting->get('config_mail_parameter');
			$mail->hostname = $this->setting->get('config_smtp_host');
			$mail->username = $this->setting->get('config_smtp_username');
			$mail->password = $this->setting->get('config_smtp_password');
			$mail->port = $this->setting->get('config_smtp_port');
			$mail->timeout = $this->setting->get('config_smtp_timeout');				
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->setting->get('config_email'));
			$mail->setSender($this->setting->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			$this->session->data['success'] = $this->text('text_success');
			$this->services->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->data['heading_title'] = $this->text('heading_title');
		$this->data['text_your_email'] = $this->text('text_your_email');
		$this->data['text_email'] = $this->text('text_email');
		$this->data['entry_email'] = $this->text('entry_email');
		$this->data['button_continue'] = $this->text('button_continue');
		$this->data['button_back'] = $this->text('button_back');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = $this->forgotten_action_link; 
		$this->data['back'] = $this->back_action_link;		
	}

	private function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->text('error_email');
		} elseif (!$this->services->get_total_customer_by_email($this->request->post['email'])) {
			$this->error['warning'] = $this->text('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>