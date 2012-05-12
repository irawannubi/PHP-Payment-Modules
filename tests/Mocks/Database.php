<?php
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments\tests\Mocks;


/**
 * Mocked class for database module settings.
 *
 */
class DatabaseMocks 
{
	/**
	 * Holds key/index values for settings.
	 * @var static array 
	 */
	private static $_settings = array(
			'AuthorizeNet_x_login' => 'b6g5bWZ83uzFjCrX++ksavc8oDM5430FXwoDaQanYBo=',
			'AuthorizeNet_x_tran_key' => 'hB9kKeg/z/hS9ucP9/tf4disr+H58F+1ON5cHXqXeDo=',
			'GoogleCheckout_merchantId' => '247275622417170',
			'GoogleCheckout_merchantKey' => 'gWPMiKTL3jlqDdpA14DzGA',
			'PayPal_business' => 'tyler_1316489459_biz@freelancerpanel.com',
			'PayPal_ipnUrl' => 'payment/paypal',
			'PayPalPro_apiUsername' => 'iO4WWHDNIP3NL57bJsawNLqFTffETS4OCAuzKbO6Nkrxfbo9rpnz+4VSpt30TfinogaEOt6tupll70dje6MXVg==',
			'PayPalPro_apiPassword' => 'IO1bJMvB/+fghkQXZTwvpmYyCcPr+9s9oA8mXVKC+Xo=',
			'PayPalPro_apiSignature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AVfbDmfa93.sdHXWloOrXupym2zE',
			'TwoCo_vendorId' => '1595373',
			'TwoCo_secretWord' => 'tango',
			);
	
	/**
	 * Returns value based on key.
	 * @param string $settingName
	 * @return string value
	 */
	public static function getSetting ($settingName)
	{
		return self::$_settings[$settingName];
	}
}