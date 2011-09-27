# CakePHP TinyUac

TinyUac provides a simple method for authenticating users preventing coding over and over the same methods.

## Configuration

### Add routes for better looking urls

	Router::connect('/users/:action/*', 
		array('admin' => null, 'plugin' => 'tiny_uac', 'controller' => 'tiny_uac_users'), 
		array('action' => '(login|logout|password_recover|password_recover_set|password_change)')
	);

### Add configuration to AppController

	class AppController extends Controller {
	
		var $components = array(
			'TinyUac.TinyUacAuth' => array(
				'Auth' => array(
					'loginRedirect' => array('admin' => null, 'plugin' => null, 'controller' => 'queue', 'action' => 'index'),
				)
			)
		);
	}

## Usage 

### Reaching over session data

	$this->TinyUacAuth->get('username');
		
## Methods automatically available

* login
* logout
* password\_recover
* password\_recover\_set
* password\_change	