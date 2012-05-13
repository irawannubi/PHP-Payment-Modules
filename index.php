<?php

use payments\Application;

/** Application */
require_once 'config/Application.php';

// Create application,
$application = new Application();


/**
 * Sample on how to call Authorize.net.
 */
$module = new payments\Modules();
$module->setModuleName('AuthorizeNet');

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
			'creditCardNumber'		=> '4007000000027',
			'x_amount'			=> '1.99',
			'description'		=> 'Sample',
			'creditCardExpDate'		=> '12/2012',
			'creditCardCode'		=> '214',
);
$response = $module->processPayment('1.99', array('invoiceId' => 1, 'description' => 'Sample'), $billingDetails);

if(!$response) {
	echo 'Denied: ';
    echo $module->getResponse();
} else {
    echo 'Approved: ';
    echo $module->getResponse();
}
