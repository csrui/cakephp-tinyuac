<?php 
/* tiny_uac schema generated on: 2011-09-17 20:12:25 : 1316290345*/
class tiny_uacSchema extends CakeSchema {
	var $name = 'tiny_uac';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 19, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'share_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'api_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'password_change_hash' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username_UNIQUE' => array('column' => 'username', 'unique' => 1), 'key_UNIQUE' => array('column' => 'api_key', 'unique' => 1), 'share_key_UNIQUE' => array('column' => 'share_key', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
}
?>