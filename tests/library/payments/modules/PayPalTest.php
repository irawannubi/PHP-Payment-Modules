<?php

class PayPalTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testGetPayPalUrl()
	{
		$module = new payments\Modules();
		$module->setModuleName('PayPal');
		
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
		
		$response = $module->processPayment('1.99', array('invoiceId' => 1), $billingDetails);
		$url = $module->getPaypalUrl();
		
		$this->assertContains('paypal.com', $url);
		
	}

}