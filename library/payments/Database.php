<?php
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments;

/**
 * A sample Database implementation wrapper.
 * Use or overwrite with your own.
 */
class Database extends Application
{
	/*
	 * PDO Handler.
	 * @var static $handle
	 */
	public static $handle;
	
	/**
	 * Static method to connect to MySQL through PDO.
	 */
	public static function factory()
	{
		if (!self::$handle) {
			self::$handle = new \PDO('mysql:host=' . self::$_dbHost . ';dbname=' . self::$_dbName, self::$_dbUsername, self::$_dbPassword);
			self::$handle->exec("SET CHARACTER SET utf8");
		}
		return self::$handle;
	}
}

?>