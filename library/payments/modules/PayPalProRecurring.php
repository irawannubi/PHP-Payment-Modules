<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace tcole\payments;

class PayPalProRecurring extends PayPalPro implements \tcole\payments\interfaces\Modules
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
			'DESC' => $billingDetails['itemDescription'],
			'BILLINGPERIOD' => $referenceArray['x_unit'],
			'BILLINGFREQUENCY' => $referenceArray['x_length'],
			'TOTALBILLINGCYCLES' => $referenceArray['x_total_occurrences'],
			'AUTH_TIMESTAMP' => time(),
			'PWD' => \tcole\General::decrypt($settings['apiPassword']),
			'USER' => \tcole\General::decrypt($settings['apiUsername']),
			'SIGNATURE' => \tcole\General::decrypt($settings['apiSignature']),
		);

		$this->fields = $defaults;
		$this->process();
	}
    
    public function process() {
    	$fields = $this->separateFields($this->fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, (!$this->debug ? self::URL_LIVE : self::URL_TEST));
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
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