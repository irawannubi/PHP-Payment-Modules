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
    	// check for unit testing values that uses a Mock Db.
    	if(APPLICATION_ENV == 'testing') {
    		// require Mock.
    		require_once(BASE_PATH . '/tests/Mocks/Database.php');
    	
    		return \payments\tests\Mocks\DatabaseMocks::getSetting($settingName);
    	}
    	
    	$db = parent::factory();

    	$sql = 'SELECT value
    		FROM payment_modules_settings
    		WHERE module_option = :option';
    	
    	$statement = $db->prepare($sql);
    	$statement->execute(array(':option' => $settingName));
    	
    	$value = $statement->fetch();
    	return $value['value'];
    }
    
    protected static function settingValuesMock($settingName) 
    {
    	
    }
}

?>