<?php

class AuthorizeNetArbTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function testAuthorizeArbSuccessResponseFromGateway()
	{
		$module = new payments\Modules();
		$module->setModuleName('AuthNetARB');
		
		// Set debug mode.
		$module->debug = true;
		
		$billingDetails = array(
				'firstName'		=> 'John ' . mt_rand(0, 100),
				'lastName'		=> 'Doe ' . mt_rand(0, 100),
				'address'			=>  mt_rand(0, 1500) . ' Main St',
				'city'			=> 'New York',
				'state'			=> 'New York',
				'zip'				=> '12345',
				'country'			=> 'US',
				'email'			=> 'fake@fake.com',
				'phone'			=> '5555555555',
				'creditCardNumber'		=> '4007000000027',
				'x_amount'			=> mt_rand(),
				'description'		=> 'Sample',
				'creditCardExpDate'		=> '12/2012',
				'creditCardCode'		=> '214',
				'referenceId'   => mt_rand(),
		);
		
		$referenceArray = array(
				'x_subsc_name'	=> 'Test Sub ' . microtime(true),
				'x_length'		=> mt_rand(0, 12),
				'x_unit'			=> 'months',
			   	'x_start_date'	=> time(),
			   	'x_total_occurrences' => mt_rand(0, 12),
		);
		$response = $module->processPayment('1.99', $referenceArray, $billingDetails);
		
		if(is_bool($response)) {
			$this->assertEquals(true, $response);
		} else {
			$this->assertContains('Successful', $response);
		}
		
	}

}