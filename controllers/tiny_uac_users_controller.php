<?php

class TinyUacUsersController extends AppController {
	
	// public function beforeFilter() {
	// 	
	// 	parent::beforeFilter();
	// 	
	// 	$this->Auth->allow('add', 'view');
	// 	
	// }

	// public function view($key = null) {
	// 
	// 	if (is_null($key)) {
	// 		$this->redirect(array('action' => 'email', $this->Auth->user('key')));
	// 	}
	// 
	// 	$this->User->Contain('Branding');
	// 	$branding = $this->User->findByShareKey($key);
	// 	$this->set('branding', $branding);
	// 	$this->set('email', $branding['User']['share_key'] . '@cargoshipapp.com');
	// 	
	// }
	
	protected function add() {
				
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->User->generateKeys($this->User->id);
				return true;
			} else {
				unset($this->data['User']['password']);
				return false;
			}
		}
		
	}

	protected function edit() {

		if (!empty($this->data)) {
			
			$this->data['User']['id'] = $this->Auth->user('id');
			return $this->User->save($this->data));
			
		}
		
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $this->Auth->user('id'));
		}
	}
	
	protected function change_password() {
		
		if (!empty($this->data)) {
			
			$this->User->id = $this->Auth->user('id');

			$this->data['User']['password_1'] = $this->Auth->password($this->data['User']['password_1']);
			$this->data['User']['password_2'] = $this->Auth->password($this->data['User']['password_2']);
			
			return $this->User->save($this->data));
			
		}
		
		unset($this->data['User']);
		
	}

	protected function login() {
		
	}
	
	protected function logout() {
		
		$this->autoRender = false;
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
		
	}
	
	protected function regenerate_keys() {
		
		$this->autoRender = false;
		
		$this->User->generateKeys($this->Auth->user('id'));		
		$this->Session->setFlash(__('New keys generated', true));		
		$this->redirect($this->referer());
		
	}
	
}
