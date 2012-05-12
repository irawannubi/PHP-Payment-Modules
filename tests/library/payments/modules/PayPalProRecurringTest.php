<?php

class PayPalProRecurringTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testPayPalProRecurringResponseFromGateway()
	{
		$module = new payments\Modules();
		$module->setModuleName('PayPalProRecurring');
		
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
			'creditCardCVV'		=> '214',
            'referenceId'   => '1111',
		);
		
		$referenceArray = array('invoiceId' => 1, 
        	'x_subsc_name' => 'Test Sub',
            'x_length' => '12',
            'x_unit' => 'Month',
            'x_start_date' => date('Y-m-d', time()) . 'T00:00:00Z',
			'itemDescription' => 'Unit test - ' . time(),
            'x_total_occurrences' => 5);
		
		$response = $module->processPayment('1.99', $referenceArray, $billingDetails);
		
		
		$this->assertEquals(true, $response);
		
	}

}