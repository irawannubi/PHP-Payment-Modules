<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace payments\modules;

/**
 * This module requires the following parameters set through payments\Modules.
 * $referenceArray is required, but you can also pass whatever you need, such as an invoiceId, userId etc.
 * Below is the required $billingDetails with sample values.
 * 
 $referenceArray = array('invoiceId' => 1, 
        	'x_subsc_name' => 'Test Sub',
            'x_length' => '12',
            'x_unit' => 'Month',
            'x_start_date' => date('Y-m-d', time()) . 'T00:00:00Z',
			'itemDescription' => 'Unit test - ' . time(),
            'x_total_occurrences' => 5);
 * Sample billing details: Notice it requires CVV and not cardCode.
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
 'creditCardCVV'	=> '214',
 'referenceId'   => '1111',
 */

class PayPalProRecurring extends PayPalPro implements \payments\interfaces\Modules
{
	
	public $debug = true;
	
	private $_gatewayUrl = '';
	
	public $requestType = 'CreateRecurringPaymentsProfile';
	
	public $authModule = '3TOKEN';
	
	protected $_response = '';
	
	
	/**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();
	

	public function __construct($amount, $referenceArray, $billingDetails, $settings) {
		$defaults = array(
			'METHOD' => $this->requestType,
			'VERSION' => $this->apiVersion,
			'AMT' => $amount,
			'CREDITCARDTYPE' => $billingDetails['creditCardType'],
			'ACCT' => $billingDetails['creditCardNumber'],
			'EXPDATE' => $billingDetails['creditCardExpDate'],
			'CVV2' => $billingDetails['creditCardCVV'],
			'FIRSTNAME' => $billingDetails['firstName'],
			'LASTNAME' => $billingDetails['lastName'],
			'STREET' => $billingDetails['address'],
			'CITY' => $billingDetails['city'],
			'STATE' => $billingDetails['state'],
			'ZIP' => $billingDetails['zip'],
			'COUNTRYCODE' => $billingDetails['country'],
			'CURRENCYCODE' => $billingDetails['currency'],
			'PROFILESTARTDATE' => $referenceArray['x_start_date'],
			'DESC' => $referenceArray['itemDescription'],
			'BILLINGPERIOD' => $referenceArray['x_unit'],
			'BILLINGFREQUENCY' => $referenceArray['x_length'],
			'TOTALBILLINGCYCLES' => $referenceArray['x_total_occurrences'],
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
		curl_setopt($ch, CURLOPT_URL, (!$this->debug ? self::URL_LIVE : self::URL_TEST));
		
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
	
	public static function returnSettings() {
		// use the parent's settings.
    	return get_parent_class();
    	
    }
    
}