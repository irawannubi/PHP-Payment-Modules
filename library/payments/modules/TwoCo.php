<?php
/**
 * 2CheckOut Class
 *
 * Integrate the 2CheckOut payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

namespace payments\modules;


class TwoCo implements \payments\interfaces\Modules
{
	public $fields;
	
	private $_gatewayUrl = '';
	
	const LIVE_URL = 'https://www.2checkout.com/checkout/purchase?';
	
	public function  __construct($amount, $referenceArray, $billingDetails, $settings) {
		
		// set URL.
		$this->_gatewayUrl = self::LIVE_URL;
		
		// set vendorId and secret word.
		$this->addField('sid', $settings['vendorId']);
		$this->addField('total', $amount);
		
		if(is_array($referenceArray)) {
			// loop through referenceArray.
			foreach($referenceArray as $field => $value) {
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
	
	public function validateCallback() {
		foreach ($_POST as $field=>$value)
        {
            $this->ipnData[$field] = $value;
        }

        $vendorNumber   = ($this->ipnData["vendor_number"] != '') ? $this->ipnData["vendor_number"] : $this->ipnData["sid"];
        $orderNumber    = $this->ipnData["order_number"];
        $orderTotal     = $this->ipnData["total"];

        // If demo mode, the order number must be forced to 1
        if($this->demo == "Y" || $this->ipnData['demo'] == 'Y')
        {
            $orderNumber = "1";
        }

        // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
        $key = strtoupper(md5($this->secret . $vendorNumber . $orderNumber . $orderTotal));

        // verify if the key is accurate
        if($this->ipnData["key"] == $key || $this->ipnData["x_MD5_Hash"] == $key)
        {
            return true;
        }
        else
        {
            return false;
        }
	}
	
	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}
	
	public static function returnSettings() {
            return array(
                'vendorId',
                'secretWord'
            );
    }
	
	public function getTwoCoUrl() {
		$fields = http_build_query($this->fields);
		
		return $this->_gatewayUrl . $fields;
	}
}