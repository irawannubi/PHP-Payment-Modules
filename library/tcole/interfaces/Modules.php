<?php

/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com> 
 */

namespace tcole\payments\interfaces;

interface Modules
{
    public function __construct($amount, $referenceArray, $billingDetails, $settings);
    
    public function process();
    
    public function isApproved();
    
    public function returnedResponse();
    
    public static function returnSettings();
}