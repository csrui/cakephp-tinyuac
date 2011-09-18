<?php

class TinyUacAuthComponent extends Object {


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
				'autoRedirect' => true,
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
				$this->Controller()->$component->$set = $val;
			}

		}
		
		// pr($this->Controller()->Auth);
		
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
		$this->Controller()->Session->destroy();
		$this->Controller()->redirect($this->Controller()->Auth->logout());
		
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
		return $this->Controller()->Auth->user($param);
	}


}
?>