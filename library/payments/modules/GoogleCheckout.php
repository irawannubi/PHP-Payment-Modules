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
 * Sample one is below:
 * $referenceArray = array(
	        	'items' => array(array(
				'price' => '1.99', 'name' => 'Test Item', 'description' => 'A very nice description', 'recurring' => 1)),
	        'recurring' => 1, 'period' => 'monthly', 'totalTimes' => 12
			);
 *
 * Below is the required $billingDetails with sample values.
 * $billingDetails = array(
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
 */


class GoogleCheckout implements \payments\interfaces\Modules
{
	public $debug = true;
	public $fields;
	private $order;
	private $_settings;
	private $_serial;
	
	private $_gatewayUrl = '';
	
	const LIVE_URL = 'checkout.google.com';
	const DEBUG_URL = 'sandbox.google.com/checkout';
	
	public function __construct($amount, $referenceArray, $billingDetails, $settings) {
		if($this->debug) {
			$this->_gatewayUrl = self::DEBUG_URL;
		} else {
			$this->_gatewayUrl = self::LIVE_URL;
		}
	
		$order = new GoogleCheckout\Order();
		$order->setMerchantId($settings['merchantId']);
		$order->setMerchantKey($settings['merchantKey']);
		$order->setCheckoutServer($this->_gatewayUrl);
		if(isset($referenceArray['period'])) {
			$order->setRecurring($referenceArray['recurring'], $referenceArray['period'], $referenceArray['totalTimes']);
		}
		
		
		// one item for now.
		for($i=0; $i < count($referenceArray['items']); $i++) {
			$item = new GoogleCheckout\Item();
			$item->setName($referenceArray['items'][$i]['name']);
			$item->setDescription($referenceArray['items'][$i]['description']);
			$item->setPrice($referenceArray['items'][$i]['price']);
			$item->setQuantity(1);
			$item->setRecurring($referenceArray['items'][$i]['recurring']);
			$order->addItem($item);
		}		
		
		$this->order = $order;
		$this->_settings = $settings;
	}
	
	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}
	
	public function getGCUrl() {
		return $this->order->getRedirectUrl();
	}
    
    public function process() {
    	
    }
    
    public function isApproved() {
    	
    }
    
    public function returnedResponse() {
    	
    }
	
	public function validateCallback($result) {
		// We are using API 2.5, utilizing serial only.
		$serialArray = array();
		parse_str($result, $serialArray);
		
		$serialNumber = $serialArray['serial-number'];
		
		// validate it.
		$history = new \SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><notification-history-request></notification-history-request>");
		$history->addChild('serial-number', $serialNumber);
		$history->addAttribute('xmlns', 'http://checkout.google.com/schema/2');
		$xml = $history->asXml();
	
		$url = "https://{$this->_settings['merchantId']}:{$this->_settings['merchantKey']}@" . $this->_gatewayUrl . "/api/checkout/v2/reports/Merchant/" . urlencode(trim($this->_settings['merchantId']));
		
		$config = array(
			'adapter'		=> 'Zend_Http_Client_Adapter_Curl',
			'curloptions'	=> array(
				CURLOPT_FOLLOWLOCATION	=> true,
				CURLOPT_POST			=> true,
				CURLOPT_SSLVERSION 		=> 3, //cURL for Travis CI.
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_SSL_VERIFYHOST => 2,
					CURLOPT_CAINFO => APP_DIR . '/certs/debug/sandbox.google.com'
			),
		);

		$client = new \Zend_Http_Client($url, $config);
		$client->setHeaders('Content-Type', 'application/xml; charset=UTF-8');
		$client->setHeaders('Accept', 'application/xml; charset=UTF-8');
		
		$return = $client->setRawData($xml, 'text/xml')->request('POST')->getBody();
		
		
		$orders = new \SimpleXMLElement($return);
		
		$status = $orders->{'fulfillment-order-state'};
		
		
		if($status != 'CHARGED' && $status != 'NEW') {
			return false;
		} else {
			$this->_serial = $serialNumber;
			return true;
		}

	}

	public function respondToSerial($status) {
		if($status == true) {
			// success message
			header('HTTP/1.0 200 OK');
			
			$success = new \SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><notification-acknowledgment></notification-acknowledgment>");
			$success->addChild('serial-number', $this->_serial);
			$success->addAttribute('xmlns', 'http://checkout.google.com/schema/2');
			$xml = $history->asXml();
			echo $xml;
			exit();
		} else {
			header('HTTP/1.0 402 Payment Required');
		}
	}
    
    public static function returnSettings() {
    	return array(
		'merchantId',
		'merchantKey');
    }
}