<?php

class PayPalProTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testPayPalProResponseFromGateway()
	{
		$module = new payments\Modules();
		$module->setModuleName('PayPalPro');
		
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
			'creditCardNumber'		=> '4765526829286575',
			'creditCardType' => 'Visa',
			'x_amount'			=> '1.99',
			'currency' => 'USD', 
			'creditCardExpDate'		=> '122012',
			'creditCardCode'		=> '214',
            'referenceId'   => '1111',
	);
		
		$response = $module->processPayment('1.99', array('invoiceId' => 1), $billingDetails);
		
		$this->assertEquals(true, $response);
		
	}

}