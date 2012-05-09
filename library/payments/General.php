<?php
/**
 * @package        Payment Methods
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html
 * @author         Tyler Cole <tyler.cole@freelancerpanel.com>
 */

namespace payments;

/**
 * @description This class is merely for reference materials only. Please use your actual app's code.
 */
class General extends Application
{	
	/**
	 * @description Returns Class name with full namespaced.
	 * @param string $classWithNamespace
	 * @return string Class without namespace attached.
	 */
	static public function getRealClassFromNamespace($classWithNamespace) {
		//@todo: clean this up.
		$explode = explode('\\', $classWithNamespace);
		$count = count($explode);
		
		return $explode[$count - 1];
	}
	
	/**
	 * Encryption based on sample encryptionKey.
	 * @param string $string
	 * @return string Encrypted string.
	 */
	static public function encrypt($string) {
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, parent::$_encryptionKey, $string, MCRYPT_MODE_ECB,
				mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
						MCRYPT_RAND))));
	}
	
	/**
	 * Decryption based on sample encryptionKey.
	 * @param string $string
	 * @return string Decrypted string.
	 */
	static public function decrypt($string) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, parent::$_encryptionKey, base64_decode($string), MCRYPT_MODE_ECB,
				mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}

}