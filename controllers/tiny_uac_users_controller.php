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

}
