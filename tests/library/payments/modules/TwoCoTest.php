<?php

class TwoCoTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testGetPayPalUrl()
	{
		$module = new payments\Modules();
		$module->setModuleName('TwoCo');
		
		// Set debug mode.
		$module->debug = true;
		
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
		
		$referenceArray = array(
	        'invoiceId' => 1, 
	        'cart_order_id' => 'Example',
			'x_Receipt_Link_URL' => 'http://example.com',
			'c_prod_1' => 1,
			'c_name_1' => 'Sample',
			'c_description_1' => 'Sample Desc',
			'c_price_1' => '1.99',
			'merchant_order_id' => 50,
			'id_type' => 1
		);
		
		$response = $module->processPayment('1.99', $referenceArray, $billingDetails);
		$url = $module->getTwoCoUrl();
		
		$this->assertContains('2checkout.com', $url);
		
	}

}