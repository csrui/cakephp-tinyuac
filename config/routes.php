<?php

// include in app/config/routes.php 
// require_once App::pluginPath('tiny_uac') .'config'. DS .'routes.php';

Router::connect('/users/:action/*', 
	array('admin' => null, 'plugin' => 'tiny_uac', 'controller' => 'tiny_uac_users'), 
	array('action' => '(login|logout|password_recover|password_recover_set|password_change)')
);


?>