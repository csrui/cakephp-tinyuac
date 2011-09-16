<?php

class TinyUacUsersController extends AppController {
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		
		# Sets the view path to the current app/views/users 
		$this->viewPath = 'users';
		
	}
	
	public function login() {

		if (!empty($this->data)) {

			$this->data = $this->TinyUacAuth->Auth->hashPasswords($this->data);

			if ($this->TinyUacAuth->Auth->login($this->data)) {
				$this->redirect($this->TinyUacAuth->Auth->loginRedirect);
			}
			
		}
		
		unset($this->data['TinyUacUser']['password']);
		
	}
	
	public function logout() {
		
		$this->autoRender = false;
		$this->Session->destroy();
		$this->redirect($this->TinyUacAuth->logout());
		
	}

	public function password_change() {
		
		if (!empty($this->data)) {
			
			$this->TinyUacUser->id = $this->TinyUacAuth->get('id');
			
			if ($this->TinyUacUser->save($this->data)) {
				$this->Session->setFlash(__('Your password was changed', true));
				$this->redirect($this->TinyUacAuth->Auth->loginRedirect);
			}
			
		}
		
		unset($this->data['TinyUacUser']);
		
	}

	public function password_recover() {
		
		if (empty($this->data)) return false;
		
		$user = $this->TinyUacUser->findByUsername($this->data['TinyUacUser']['username']);

		if (empty($user)) {

			$this->TinyUacUser->invalidate('username', __('Sorry, we cant find any account with that e-mail address', true));
			return false;

		}
		
		# GENERATE NEW HASH AND SAVE IT
		$new_hash = Security::hash($user['TinyUacUser']['username'].time(), null, true);
		$this->TinyUacUser->id = $user['TinyUacUser']['id'];		
		$this->TinyUacUser->saveField('password_change_hash', $new_hash, false);
		
		# SEND AN EMAIL WITH AN URL TO RESET THE PASSWORD
		$hashed_url = Router::url(array('plugin' => 'tiny_uac', 'controller' => 'tiny_uac_users', 'action' => 'password_recover_set', $new_hash));

		$this->set(array(
			'email' => $user['TinyUacUser']['username'],
			'new_hash' => $new_hash,
			'hashed_url' => configure::read('App.url') . $hashed_url
		));
		
		// $this->EmailQueue->to = $user['UacUser']['email'];
		// $this->EmailQueue->from = sprintf('%s <%s>', Configure::read('App.name'), Configure::read('Email.username'));
		// $this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('password recovery', true));
		// $this->EmailQueue->template = $this->controller->action;
		// $this->EmailQueue->sendAs = 'both';
		// $this->EmailQueue->delivery = 'db';
		// $this->EmailQueue->send();

		$this->Session->setFlash(__('You will receive an e-mail shortly', true));
		$this->redirect('/');
		
	}

	public function password_recover_set($password_hash = null) {
		
		if (!empty($this->data['TinyUacUser']['password_change_hash'])) {
			$password_hash = $this->data['TinyUacUser']['password_change_hash'];
		}
		
		if (empty($password_hash)) {
			$this->Session->setFlash(__('Invalid password hash', true));
			$this->redirect('/');
		}
			
		$user = $this->TinyUacUser->findByPasswordChangeHash($password_hash);
		
		if (empty($user)) {
			$this->Session->setFlash(__('Invalid password hash', true));
			$this->redirect('/');
		}
		
		if (empty($this->data) || empty($this->data['TinyUacUser']['password'])) {
			$this->data['TinyUacUser']['password_change_hash'] = $password_hash;
			return false;
		}
		
		$new_password = Security::hash($this->data['TinyUacUser']['password'], null, true);
		$this->TinyUacUser->id = $user['TinyUacUser']['id'];		
		$this->TinyUacUser->saveField('password', $new_password);
		$this->TinyUacUser->saveField('password_change_hash', null);
		
		$this->Session->setFlash(__('Your new password is set', true));
		$this->redirect('/');		
		
	}

}
