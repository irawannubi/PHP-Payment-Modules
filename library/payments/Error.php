<?php
/**
* @package        Payment Methods
* @license        http://www.apache.org/licenses/LICENSE-2.0.html
* @author         Tyler Cole <tyler.cole@freelancerpanel.com>
*/

namespace payments;

/**
 * @description This class is merely for reference materials only. Please use your actual app's code.
 *
 */
class Error
{
	/**
	 * @description Sample error class.
	 * @param int Error ID.
	 * @param string Message of error
	 * @return Exception
	 */
	static public function fatalError($errorCode, $errorMessage) {
		throw new \Exception("Error Code: $errorCode, Message: $errorMessage");
	}

}