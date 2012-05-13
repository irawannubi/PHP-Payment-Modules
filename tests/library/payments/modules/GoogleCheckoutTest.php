<?php

class GoogleCheckoutTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testGoogleCheckoutSuccessResponseFromGateway()
	{
		$module = new payments\Modules();
		$module->setModuleName('GoogleCheckout');
		
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
		
		$response = $module->processPayment('1.99', 
	        array(
	        	'items' => array(array(
				'price' => '1.99', 'name' => 'Test Item', 'description' => 'A very nice description', 'recurring' => 1)),
	        'recurring' => 1, 'period' => 'monthly', 'totalTimes' => 12
			), $billingDetails);
		
		$callBack = $module->validateCallback('serial-number=423633109194481-00001-7');
		$this->assertEquals($callBack, true);
	}

}