<?php

class TinyUacAuthComponent extends Object {

	var $components = array(
		'Auth', 
		'Session'
	);
	
	private $controller = null;
	
	private $user = null;
	
	
	public function initialize(&$controller, $custom_settings = array()) {
		
		// saving the controller reference for later use
		$this->controller =& $controller;
		
		$default_settings = array(
			'Auth' => array(
				'userModel' => 'TinyUac.TinyUacUser',
				'userScope' => array('TinyUacUser.active' => 1),
				'fields' => array('username' => 'username', 'password' => 'password'),
				'autoRedirect' => false,
				'loginAction' => array('admin' => null, 'plugin' => 'tiny_uac', 'controller' => 'tiny_uac_users', 'action' => 'login'),
				'loginRedirect' => '/',
				'logoutRedirect' => '/',
				'authorize' => 'controller'
			)
		);
		
		$custom_settings = set::merge($default_settings, $custom_settings);

		# Set custom settings		
		foreach($custom_settings as $component => $settings) {
		
			foreach($settings as $set => $val) {
				$this->$component->$set = $val;
			}

		}
		
		$this->user = ClassRegistry::init('TinyUac.TinyUacUser');
				
	}

	//called after Controller::beforeFilter()
	public function startup(&$controller) {
	}
	//called after Controller::beforeRender()
	public function beforeRender(&$controller) {
	}
	//called after Controller::render()
	public function shutdown(&$controller) {
	}
	//called before Controller::redirect()
	public function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}
	
	
	public function logout() {
		
		$this->Controller()->autoRender = false;
		$this->Session->destroy();
		$this->Controller()->redirect($this->Auth->logout());
		
	}
	
	/**
	 * Alias for self::controller
	 *
	 * @return obj
	 * @author Rui Cruz
	 */
	public function Controller() {
		return $this->controller;
	}
	

	/**
	 * Alias for TinyUacUser model
	 *
	 * @return obj
	 * @author Rui Cruz
	 */
	public function User() {
		return $this->user;
	}

	/**
	 * Alias for Auth::user
	 *
	 * @return mixed
	 * @author Rui Cruz
	 */
	public function get($param = null) {
		return $this->Auth->user($param);
	}


}
?>