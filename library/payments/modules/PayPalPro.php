<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace payments\modules;

/**
 * This module requires the following parameters set through payments\Modules.
 * $referenceArray optional, pass whatever you need, such as an invoiceId, userId etc.
 * Below is the required $billingDetails with sample values.
 * $billingDetails = array(
 'firstName'		=> 'John',
 'lastName'		=> 'Doe',
 'address'		=> '121 Main St',
 'city'			=> 'New York',
 'state'			=> 'New York',
 'zip'			=> '12345',
 'country'		=> 'US',
 'email'			=> 'fake@fake.com',
 'phone'			=> '5555555555',
 'creditCardNumber'		=> '4007000000027',
 'x_amount'		=> '1.99',
 'description'	=> 'Sample',
 'creditCardExpDate'	=> '12/2012',
 'creditCardCode'	=> '214',
 'referenceId'   => '1111',
 */

class PayPalPro implements \payments\interfaces\Modules
{
	
	public $debug = true;
	
	private $_gatewayUrl = '';
	
	public $requestType = 'doDirectPayment';
	
	public $authModule = '3TOKEN';
	
	public $apiVersion = '65.1';
	
	protected $_response = '';
	
	
	/**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();
	
	const LIVE_URL = 'https://api-3t.paypal.com/nvp';
	const URL_TEST = 'https://api-3t.sandbox.paypal.com/nvp';
	
	public function __construct($amount, $referenceArray, $billingDetails, $settings) {
		$defaults = array(
			'METHOD' => $this->requestType,
			'VERSION' => $this->apiVersion,
			'AMT' => $amount,
			'CREDITCARDTYPE' => $billingDetails['creditCardType'],
			'ACCT' => $billingDetails['creditCardNumber'],
			'EXPDATE' => $billingDetails['creditCardExpDate'],
			'CVV2' => $billingDetails['creditCardCode'],
			'FIRSTNAME' => $billingDetails['firstName'],
			'LASTNAME' => $billingDetails['lastName'],
			'STREET' => $billingDetails['address'],
			'CITY' => $billingDetails['city'],
			'STATE' => $billingDetails['state'],
			'ZIP' => $billingDetails['zip'],
			'COUNTRYCODE' => $billingDetails['country'],
			'CURRENCYCODE' => $billingDetails['currency'],
			'AUTH_TIMESTAMP' => time(),
			'PWD' => \payments\General::decrypt($settings['apiPassword']),
			'USER' => \payments\General::decrypt($settings['apiUsername']),
			'SIGNATURE' => $settings['apiSignature'],
		);
		$this->fields = $defaults;
		$this->process();
	}
    
    public function process() {
    	$fields = $this->separateFields($this->fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, (!$this->debug ? self::LIVE_URL : self::URL_TEST));
		
		if(APPLICATION_ENV == 'testing') {
			//turning off the server and peer verification(TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$headers = array('X-PP-AUTHORIZATION: ');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_HEADER, false);
		
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);

		//getting response from server
		$this->_response = curl_exec($ch);
		
    }
    
    public function isApproved() {
    	$formattedResponse = $this->returnedResponse();
		
		if(strtoupper($formattedResponse['ACK']) != 'SUCCESS') {
			return false;
		} else {
			return true;
		}
    }
	
	public function separateFields($fieldArray) {
		return http_build_query($fieldArray);
	}
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */
	public function deformatNVP($nvpstr) {
	
		$intial=0;
	 	$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}
	
	public function returnedResponse() {
    	return $this->deformatNVP($this->_response);
    }
    
    public static function returnSettings() {
    	return array(
    	'apiUsername',
    	'apiPassword',
    	'apiSignature');
    	
    }
}