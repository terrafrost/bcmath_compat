<?php

use bcmath_compat\BCMath;

/**
 * @requires extension bcmath
 */
class BCMathTest extends PHPUnit\Framework\TestCase
{
	public function testDemo()
	{
		//$this->expectException('PHPUnit\\Framework\\Error\\Deprecated');
		try {
		trigger_error('zzz', E_USER_DEPRECATED);
		} catch (\Throwable $e) {
		echo $e::class;
		}
	}

}