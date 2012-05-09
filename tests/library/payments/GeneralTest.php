<?php

class GeneralTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	
	public function does1Plus1Equal2Test()
	{
		$var = 1 + 1;
		
		$this->assertNotEmpty($var);
	}

}