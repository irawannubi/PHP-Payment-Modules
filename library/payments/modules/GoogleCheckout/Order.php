<?php

namespace payments\modules\GoogleCheckout;

class Order {

	/**
	 * @var array[int]Item
	 */
	protected $_items = array();

	/**
	 * Configuration array, set using the constructor or using ::setConfig()
	 *
	 * @var array
	 */
	public $config = array(
		'merchant_id'		=> null,
		'merchant_key'		=> null,
		'callback_url'		=> null,
		'checkout_server'	=> null,
		'recurring'			=> false,
		'period'			=> false,
		'totalTimes'		=> false,
	);
	
	
	public function __construct($config = array()) {
		$this->setOptions($config);
		
	}
	
	/**
	 * Set configuration parameters
	 *
	 * @param  Zend_Config | array $config
	 * @return Order
	 * @throws Exception
	 */
	public function setOptions($config = array()) {
		if ($config instanceof \Zend_Config) {
			$config = $config->toArray();
		} elseif (!is_array($config)) {
			throw new \Exception('Array or Zend_Config object expected, got ' . gettype($config));
		}
		
		foreach ($config as $k => $v) {
			$this->config[strtolower($k)] = $v;
		}
		
		return $this;
	}
	
	public function setMerchantId($merchantid) {
		$this->config['merchant_id'] = $merchantid;
		
	}
	
	public function setMerchantKey($merchantkey) {
		$this->config['merchant_key'] = $merchantkey;
	}
	
	public function setCheckoutServer($server) {
		$this->config['checkout_server'] = $server;
	}
	
	public function setRecurring($recurring, $period, $totalTimes, $startDate = '') {
		$this->config['recurring'] = $recurring;
		$this->config['period'] = $period;
		$this->config['totalTimes'] = $totalTimes;
	}
	
	/**
	 * 
	 * 
	 * @param Item $item
	 * @return Order
	 */
	public function addItem(Item $item) {
		if (!$item->isValid()) {
			throw new \Exception("You need to provide all the required options for the Item");
		}
		$this->_items[] = $item;
		return $this;
	}
	
	public function getRedirectUrl() {
		if (!count($this->_items)) {
			throw new \Exception("You must add at least one item!");
		}
		
		$checkout = new \SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><checkout-shopping-cart></checkout-shopping-cart>");
		$checkout->addAttribute('xmlns', 'http://checkout.google.com/schema/2');
		$shopping_cart = $checkout->addChild('shopping-cart');
		

				$items = $shopping_cart->addChild('items');
				$itemObjs = $this->_items;

				foreach ($itemObjs as $item_obj) {
				$item = $items->addChild('item');
					
				if($item_obj->getRecurring()) {
					// set price to 0 since it's recurring.
					$item->addChild('item-name', $item_obj->getName());
					$item->addChild('item-description', $item_obj->getDescription());
					$item->addChild('quantity', 1);
					$price = $item->addChild('unit-price', 0);
					$price->addAttribute('currency', 'USD');
					$sub = $item->addChild('subscription');
					$sub->addAttribute('type', 'merchant');
					$sub->addAttribute('period', strtoupper($this->config['period']));
					$payment = $sub->addChild('payments');
					$subTimes = $payment->addChild('subscription-payment');
					$subTimes->addAttribute('times', $this->config['totalTimes']);
					
					$maxCharge = $subTimes->addChild('maximum-charge', $item_obj->getPrice());
					$maxCharge->addAttribute('currency', 'USD');

					/* Google Docs state this is required, and yet it causes it to fail. Major WTF!
					$recItem = $sub->addChild('recurrent-item');
					$recItem->addChild('item-name', $item_obj->getName());
					$recItem->addChild('quantity', $item_obj->getQuantity());
					$price = $recItem->addChild('unit-price', $item_obj->getPrice());
					$price->addAttribute('currency', 'USD');
					$recItem->addChild('item-name', $item_obj->getName());
					$recItem->addChild('item-description', $item_obj->getDescription());
					 *
					 */
					
						
				} else {
					$price = $item->addChild('unit-price', $item_obj->getPrice());
					$price->addAttribute('currency', 'USD');
					$item->addChild('quantity', $item_obj->getQuantity());
					$item->addChild('item-name', $item_obj->getName());
					$item->addChild('item-description', $item_obj->getDescription());
				}

				
			}

				$xml = $checkout->asXML();

				$url =
 "https://{$this->config['merchant_id']}:{$this->config['merchant_key']}@" . $this->config['checkout_server'] . "/api/checkout/v2/merchantCheckout/Merchant/" . urlencode(trim($this->config['merchant_id']));
		/*
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
		
		$return = $client->setRawData($xml, 'text/xml')->request('POST')->getBody(); */
				
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSLVERSION, 3);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_CAINFO, APP_DIR . '/certs/debug/sandbox.google.com');
				curl_setopt($ch, CURLOPT_HEADER, array('Accept: application/xml; charset=UTF-8'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/xml; charset=UTF-8'));
				
				$return = curl_exec($ch);
				curl_close($ch);
		
		$return = new \SimpleXMLElement($return);
		if (!isset($return->{'redirect-url'})) {
			throw new \Exception("An Error has occurred");
		}
		$url = current($return->{'redirect-url'});
		return $url;
		
	}
	
	
	
	
	
	
	
	
}

