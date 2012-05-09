<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace payments;

class Modules extends General
{
    /**
     * Name of module being called.
     * @var static string 
     */
    public static $_moduleName;
    
    /**
     * Setting names for this module.
     * @var static array 
     */
    protected static $_moduleValueNames;
    
    /**
     * Specific Payment Module Object.
     * @var static object
     */
    private static $_moduleObj;
    
    /**
     * Sets the module called, and then includes the module. Otherwise, throw error.
     * @param string $moduleName Name of module.
     * @return bool true if succesful.
     */
    public function setModuleName($moduleName) {
    	// check to make sure the module exists.
 
    	if(!self::moduleExists($moduleName)) {
        	\payments\Error::fatalError(100, 'Module not found.');
        }
        self::$_moduleName = $moduleName;

		// load the class.
        require_once 'modules' . DIRECTORY_SEPARATOR . $moduleName . '.php';
        
        return true;
    }
	
    /**
     * The main workhorse of the project. This method
     * will pass all of your details to the necessary module
     * and process payment, and then if it's true/false.
     * @param decimal $amount
     * @param array See the particular module's documentation for more info.
     * @param array See the particular module's documentation for more info.
     * @return bool What happened with the payment.
     */
    public function processPayment($amount = '', $referenceArray = '', $billingDetails = false) {

        self::getModuleSettingNames();
        $settingValues = self::getModuleSettingValues();
   
        $moduleName = __NAMESPACE__ . '\\modules\\' . self::$_moduleName;
        $module = new $moduleName($amount, $referenceArray, $billingDetails, $settingValues);
        $module->process();
        
        self::$_moduleObj = $module;
        
        return $module->isApproved();
        
    }
    
    /**
     * Returns raw response from the module.
     * @return mixed 
     */
    public function getResponse() {
        $response = self::$_moduleObj->returnedResponse();
        return $response;
    }
    
    /**
     * Sets the moduleValueNames based on what the set module requires.
     */
    protected static function getModuleSettingNames() {
    	// this function will return either the parent class, or an array of settings.
        self::$_moduleValueNames = call_user_func(array(__NAMESPACE__ . '\\modules\\' . self::$_moduleName, 'returnSettings'));
    }
    
    /**
     * Gets the values for the required moduleValueNames
     * @return array Values for the settings.
     */
    protected static function getModuleSettingValues() {
        $values = array();

		// if it's an object, we need to go to the parent for its settings.
		if(!is_array(self::$_moduleValueNames)) {
			$settingsObj = \payments\General::getRealClassFromNamespace(self::$_moduleValueNames);
			self::$_moduleValueNames = call_user_func(array('\\' . self::$_moduleValueNames, 'returnSettings'));
			
		} else {
			$settingsObj = self::$_moduleName;
		}
		
		foreach(self::$_moduleValueNames as $name) {
	            $settingName = $settingsObj . '_' . $name;
	
	            // get the values for each setting for this module.
	            $values[$name] = \payments\Settings::$settingName();
	            
	        }
        
        return $values;
    }
    
	/**
	 * Checks to see if a module exists.
	 * @param string $moduleName
	 * @return bool
	 */
	protected static function moduleExists($moduleName) {
		if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleName . '.php')) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Magic method to call on the obj rather than this class.
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method, $args) {
		if(!method_exists($this, $method)) {
			return call_user_func_array(array(self::$_moduleObj, $method), $args);
		}
	}
    
}