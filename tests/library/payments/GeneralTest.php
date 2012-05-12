<?php

class GeneralTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		// string to test encryption with.
		$this->toEncrypt = 'abcd1234';
	}
	
	public function testNamespaceRemovalFromClass()
	{
		$class = 'payments\\module\\Test';
		$classResult = payments\General::getRealClassFromNamespace($class);
		$expectedResult = 'Test';
		
		$this->assertEquals($expectedResult, $classResult);
	}
	
	public function testEncryptionDecryption()
	{
		$encrypt = payments\General::encrypt($this->toEncrypt);
		$decrypt = payments\General::decrypt($encrypt);
		
		$this->assertEquals($decrypt, $this->toEncrypt);
	}

}