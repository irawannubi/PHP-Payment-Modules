<?php
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments\tests;

// start output buffering
ob_start();

// set our app paths and environments
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('TEST_PATH', BASE_PATH . '/tests');
define('APPLICATION_ENV', 'testing');

// Set the default timezone
date_default_timezone_set('America/New_York');

require_once BASE_PATH . '/config/Application.php';
// Create application,
$application = new \payments\Application();
