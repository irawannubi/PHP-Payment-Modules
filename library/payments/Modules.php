<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace tcole\payments;

class Modules extends \tcole\General
{
    /**
     * @var string Name of module being called.
     */
    
    public static $_moduleName;
    
    /**
     * @var array Setting names for this module.
     */
    protected static $_moduleValueNames;
    
    /**
     * @var object Specific Payment Module Object.
     */
    private static $_moduleObj;
    
    /**
     *
     * @param string $moduleName Name of module.
     */
    
    public function setModuleName($moduleName) {
    	// check to make sure the module exists.
 
    	if(!self::moduleExists($moduleName)) {
        	\tcole\Error::fatalError(100, 'Module not found.');
        }
        self::$_moduleName = $moduleName;

		// load the class.
        require_once $moduleName . '.php';
    }
	
    public function processPayment($amount = '', $referenceArray = '', $billingDetails = false) {

        self::getModuleSettingNames();
        $settingValues = self::getModuleSettingValues();
        $moduleName = __NAMESPACE__ . '\\' . self::$_moduleName;
        $module = new $moduleName($amount, $referenceArray, $billingDetails, $settingValues);
        $module->process();
        
        self::$_moduleObj = $module;
        
        return $module->isApproved();
        
    }
    
    public function getResponse() {
        $response = self::$_moduleObj->returnedResponse();
        return $response;
    }
    
    protected static function getModuleSettingNames() {
    	// this function will return either the parent class, or an array of settings.
        self::$_moduleValueNames = call_user_func(array(__NAMESPACE__ . '\\' . self::$_moduleName, 'returnSettings'));
    }
    
    protected static function getModuleSettingValues() {
        $values = array();

		// if it's an object, we need to go to the parent for its settings.
		if(!is_array(self::$_moduleValueNames)) {
			$settingsObj = \tcole\General::getRealClassFromNamespace(self::$_moduleValueNames);
			self::$_moduleValueNames = call_user_func(array('\\' . self::$_moduleValueNames, 'returnSettings'));
			
		} else {
			$settingsObj = self::$_moduleName;
		}
		
		foreach(self::$_moduleValueNames as $name) {
	            $settingName = 'payment_' . $settingsObj . '_' . $name;
	
	            // get the values for each setting for this module.
	            //@todo: refactor this to support a sample MySQL db.
	            $values[$name] = \tcole\General::$settingName();
	            
	        }
        
        return $values;
    }
	
	protected static function moduleExists($moduleName) {
		if(file_exists(__DIR__ . DIR_SEP . $moduleName . PHP_EXT)) {
			return true;
		} else {
			return false;
		}
	}
	
	// Magic method to call on the obj rather than this class.
	public function __call($method, $args) {
		if(!method_exists($this, $method)) {
			return call_user_func_array(array(self::$_moduleObj, $method), $args);
		}
	}
    
}