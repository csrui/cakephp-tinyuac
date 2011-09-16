<?php

class TinyUacUser extends AppModel {
	
	public $displayField = 'username';
	
	public $useTable = 'users';
	
	var $validate = array(
		'username' => array(
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
			'notempty' => array(
				'rule' => array('checkCurrentPassword'),
				'message' => 'Current password doesn\'t match',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password_2' => array(
	        'rule' => array('passwordsMatch'),
	        'message' => 'Your new password doesn\'t match',
	    )
		
	);

	
	public function passwordsMatch($password_2) {
		
		# If password_2 is empty then fail
		if (strlen($password_2['password_2']) == false) return false;

		# Check if password_1 is equal to password_2
		return $password_2['password_2'] == $this->data[$this->alias]['password_1'];
		
	}
	
	public function checkCurrentPassword($password) {
		
		if (!is_numeric($this->id)) return false;
		
		$current_password = $this->field('password');
		$password = Security::hash($password['password'], null, true);
		
		return $current_password == $password;
		
	}
	
	public function beforeSave($options) {
			
		if (!empty($this->data[$this->alias]['password_1'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password_1'], null, true);
			return true;
		}
		
	}
	
	public function generateKeys($user_id) {
		
		$this->recursive = -1;
		$data = $this->read(null, $user_id);
		
		$hash = Security::hash($data[$this->alias]['username'].$data[$this->alias]['created'].$data[$this->alias]['password']);
		
		$data[$this->alias] = array(
			'share_key' => substr($hash, rand(0, 14), 8),
			'api_key' => $hash
		);
		
		unset($data[$this->alias]['created']);
		unset($data[$this->alias]['modified']);

		return $this->save($data);
		
	}
	
}
