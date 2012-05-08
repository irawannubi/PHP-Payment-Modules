<?php 
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments;

/**
 * This class is a sample configuration used to connecting to the database.
 * When integrating this into your application, be sure to use your own hooks.
 * By default it uses MySQL in library/payments/Database.php, but can be easily changed.
 */
class Application
{
	/**
	 * Database host.
	 * @var static string $_dbHost
	 */
	protected static $_dbHost = 'localhost';
	
	/**
	 * Database Username.
	 * @var static string $_dbUsername
	 */
	protected static $_dbUsername = '';
	
	/**
	 * Database Password.
	 * @var static string $_dbPassword
	 */
	protected static $_dbPassword = '';
	
	/**
	 * Database Name (with prefix).
	 * @var static string $_dbName
	 */
	protected static $_dbName = '';
}