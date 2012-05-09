<?php
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments;

/**
 * A sample class to fetch settings from the database.
 */
class Settings extends Database
{
	/**
	 * Settings to be fetched.
	 * @var mixed
	 */	
	public static $settings = array();
	
	/**
	 * All settings are called static to this class that delegates it.
	 * @param mixed $func
	 * @param array $args
	 */
	public static function __callStatic($func, $args) {
        if (!empty($args)) {
            self::$settings[$func] = $args[0];
        } else {
        	return self::getSettingValue($func);
        }
    }
    
    /**
     * Sample implementation
     * @param array $settingName
     */
    public static function getSettingValue($settingName)
    {
    	$db = parent::factory();

    	$sql = 'SELECT value
    		FROM payment_modules_settings
    		WHERE module_option = :option';
    	
    	$statement = $db->prepare($sql);
    	$statement->execute(array(':option' => $settingName));
    	
    	$value = $statement->fetch();
    	return $value['value'];
    }
}

?>