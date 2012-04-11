<?php

abstract class TinyUacUsersController extends AppController {
	

	public function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->Auth->allow('password_recover', 'password_recover_set');
		
	}
	
	abstract public function login();
	
	abstract public function logout();


	/**
	 * Changes the user's password
	 *
	 * @param int $user_id 
	 * @return bool
	 * @author Rui Cruz
	 */
	protected function password_change($user_id) {
		
		if (!empty($this->request->data)) {
			
			$this->User->id = $user_id;
			
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your password was changed'));
				return true;
			}
			
		}
		
		return false;
		
	}

	/**
	 * Step 1 of the password recovery process
	 *
	 * @return mixed
	 * @author Rui Cruz
	 */
	protected function password_recover() {	
				
		# GENERATE NEW HASH AND SAVE IT
		return $this->User->generatePasswordChangeHash($this->request->data);
				
	}

	protected function password_recover_set($password_hash = null) {
		
		if (!empty($this->request->data['User']['password_change_hash'])) {
			$password_hash = $this->data['User']['password_change_hash'];
		}
		
		if (empty($password_hash)) {
			$this->Session->setFlash(__('Invalid password hash'));
		}
			
		$user = $this->User->findByPasswordChangeHash($password_hash);
		
		if (empty($user)) {
			$this->Session->setFlash(__('Invalid password hash'));
		}
		
		if (empty($this->request->data) || empty($this->request->data['User']['password'])) {
			$this->request->data['User']['password_change_hash'] = $password_hash;
			return false;
		}
		
		$this->User->id = $user['User']['id'];		
		if ($this->User->saveField('password', $this->request->data['User']['password'], true)) {

			$this->User->saveField('password_change_hash', null);		
			$this->Session->setFlash(__('Your new password is set'));
			return $user;	
			
		}
		
		return false;
		
	}

}
