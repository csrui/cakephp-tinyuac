<?php

abstract class TinyUacUser extends AppModel {
	
	public $displayField = 'username';
	
	public $useTable = 'users';
	
	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'You e-mail is not valid',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'That e-mail is already ocuppied',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(			
			'minlen' => array(
				'rule' => array('minLength', 5),
				'message' => 'You password should be at least 5 characters long'
			)
		),
		'password_2' => array(
	        'rule' => array('passwordsMatch'),
	        'message' => 'Your passwords don\'t match',
	    ),
		'password_old' => array(
			'check_current' => array(
				'rule' => array('checkCurrentPassword'),
				'message' => 'Current password doesn\'t match',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
			),
		)
		
	);
	
	/**
	 * Check if password and password confirm are equal
	 *
	 * @param string $password_2 
	 * @return bool
	 * @author Rui Cruz
	 */
	public function passwordsMatch($password_2) {
		
		# If password_2 is empty then fail
		if (strlen($password_2['password_2']) == false) return false;

		# Check if password is equal to password_2
		return $password_2['password_2'] == $this->data[$this->alias]['password'];
		
	}
	
	public function checkCurrentPassword($password) {
		
		if (!is_numeric($this->id)) return false;
		
		$current_password = $this->field('password');		
		$password = Security::hash($password['password_old'], null, true);
		
		return $current_password == $password;
		
	}
	
	public function beforeSave($options) {
			
		if (!empty($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
			return true;
		}
		
		return parent::beforeSave($options);
		
	}
	
	/**
	 * Generates a new password change authorization hash and returns user data
	 *
	 * @param array $user 
	 * @return mixed
	 * @author Rui Cruz
	 */
	public function generatePasswordChangeHash($data) {
		
		$user = $this->findByEmail($data[$this->alias]['email']);

		if (empty($user)) {

			$this->invalidate('email', __('Sorry, we cant find any account with that e-mail address'));
			return false;

		}
		
		$new_hash = Security::hash($user, null, true);
		$this->id = $user[$this->alias]['id'];		
		if ($this->saveField('password_change_hash', $new_hash, false)) {
			$user[$this->alias]['password_change_hash'] = $new_hash;
			return $user;
		}
		
		return false;
		
	}
	
	// public function generateKeys($user_id) {
	// 	
	// 	$this->recursive = -1;
	// 	$data = $this->read(null, $user_id);
	// 	
	// 	$hash = Security::hash($data[$this->alias]['username'].$data[$this->alias]['created'].$data[$this->alias]['password']);
	// 	
	// 	$data[$this->alias] = array(
	// 		'share_key' => substr($hash, rand(0, 14), 8),
	// 		'api_key' => $hash
	// 	);
	// 	
	// 	unset($data[$this->alias]['created']);
	// 	unset($data[$this->alias]['modified']);
	// 
	// 	return $this->save($data);
	// 	
	// }
	
}
