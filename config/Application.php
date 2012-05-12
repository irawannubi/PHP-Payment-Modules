<?php 
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments;
use Symfony\Component\ClassLoader as cl;

/**
 * This class is a sample configuration used to connecting to the database.
 * When integrating this into your application, be sure to use your own hooks.
 * By default it uses MySQL in library/payments/Database.php, but can be easily changed.
 */
class Application
{
	/**
	 * Database host.
	 * @var static string
	 */
	protected static $_dbHost = '';
	
	/**
	 * Database Username.
	 * @var static string 
	 */
	protected static $_dbUsername = '';
	
	/**
	 * Database Password.
	 * @var static string
	 */
	protected static $_dbPassword = '';
	
	/**
	 * Database Name (with prefix).
	 * @var static string 
	 */
	protected static $_dbName = '';
	
	/**
	 * Sample Encryption Key.
	 * @var static string
	 */
	protected static $_encryptionKey = 'sample';
	
	/**
	 * When the class is instantiated, handle autoloading through Symfony. 
	 */
	public function __construct()
	{
		$this->_autoload();
		
	}
	
	protected function _autoload()
	{
		// Ensure library/ is on include_path for Zend.
		set_include_path(implode(PATH_SEPARATOR, array(
		realpath(__DIR__ . '/../library'),
		get_include_path(),
		)));
		
		// set dir.
		if(!defined('APP_DIR')) {
			define('APP_DIR', __DIR__ . '/..');
		}
		
		// Zend won't work with Symfony...for whatever reason.
		
		require_once __DIR__.'/../library/Zend/Loader/Autoloader.php';
		
		$autoloader = \Zend_Loader_Autoloader::getInstance();
		
		require_once __DIR__.'/../library/Symfony/ClassLoader/UniversalClassLoader.php';

		$loader = new cl\UniversalClassLoader();
		
		$loader->registerNamespaces(array(
		    'payments' => __DIR__.'/../library',
		));
		

		$loader->register();
	}
	
}