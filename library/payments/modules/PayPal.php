<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace payments\modules;

/**
 * This module requires the following parameters set through payments\Modules.
 * $referenceArray is not required, you can also pass whatever you need, such as an invoiceId, userId etc.
 $billingDetails = array(
				'firstName'		=> 'John',
				'lastName'		=> 'Doe',
				'address'			=> '121 Main St',
				'city'			=> 'New York',
				'state'			=> 'New York',
				'zip'				=> '00000',
				'country'			=> 'US',
				'email'			=> 'fake@fake.com',
				'phone'			=> '5555555555',
				'x_amount'			=> '1.99',
				'description'		=> 'Sample',
		);
* Additionally, the module will only generate a URL. Please use header() or JS to redirect.
* Also, you will need to create the IPN callback function to mark as paid, or whatever you need for your app.		
 */

class PayPal implements \payments\interfaces\Modules
{
	public $debug = false;
	
	private $_gatewayUrl = '';
	
	/**
     * IPN post values as array
     *
     * @var array
     */
    public $ipnData = array();
	
	/**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipnResponse;
	
	/**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();
	
	const LIVE_URL = 'https://www.paypal.com/cgi-bin/webscr?';
	const URL_TEST = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	
	public function  __construct($amount, $referenceArray, $billingDetails, $settings) {
		if(!$this->debug) {
			$this->_gatewayUrl = self::LIVE_URL;
		} else {
			$this->_gatewayUrl = self::URL_TEST;
		}
		
		if(!empty($amount)) {
			// add amount.
			$this->addField('amount', $amount);
		}
		
		if(is_array($referenceArray)) {
			// loop through referenceArray.
			foreach($referenceArray as $field => $value) {
				$this->addField($field, $value);
			}
		}
		
		if(is_array($settings)) {
			// settings also contained necessary information.
			foreach($settings as $field => $value) {
				$this->addField($field, $value);
			}
		}
	}
	
	public function process() {
		
	}
    
    public function isApproved() {
    	
    }
    
    public function returnedResponse() {
    	
    }
    
    public static function returnSettings() {
    	return array(
    		'business',
    		'ipnUrl'
		);
    }
	
	public function validateCallback() {
		$urlParsed = parse_url($this->_gatewayUrl);

		// generate the post string from the _POST vars
		$postString = '';
		
		// Sadly PayPal doesn't give us much choice but $_POST, so we'll use that.
		
		foreach($_POST as $field => $value) {
			$this->ipnData[$field] = $value;
			$postString .= $field . '=' . urlencode(stripslashes($value)) . '&';
		}
		
		// ipn command.
		$postString .= "cmd=_notify_validate";
		
		// open connection to Paypal.
		$fp = fsockopen($urlParsed['host'], '80', $errNum, $errStr, 30);
		
		if(!$fp) {
			\payments\Error::fatalError('100', 'Could not open socket.');
		} else {
			// Post the data back to paypal

			fputs($fp, "POST $urlParsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $urlParsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");
			
			// loop through the response from the server and append to variable
			while(!feof($fp))
			{
				$this->ipnResponse .= fgets($fp, 1024);
			}

		 	fclose($fp); // close connection
		 	
		 	if(stristr($this->ipnResponse, 'VERIFIED')) {
		 		return true;
		 	} else {
		 		return false;
		 	}
		}
	}

	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}
	
	public function getPaypalUrl() {
		$fields = http_build_query($this->fields);
		
		return $this->_gatewayUrl . $fields;
	}
}