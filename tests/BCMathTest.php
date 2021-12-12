<?php

use bcmath_compat\BCMath;

/**
 * @requires extension bcmath
 */
class BCMathTest extends PHPUnit\Framework\TestCase
{
    public function testNullParams()
    {
        if (version_compare(PHP_VERSION, '8.1.0') >= 0) {
            $this->setExpectedException('PHPUnit_Framework_Error_Deprecated');
        } else {
            $this->markTestSkipped('< PHP 8.1.0 has different behavior than >= PHP 8.1.0');
        }
try {
        $a = bcadd(null, '9');
} catch (\Throwable $e) {
  echo 'zzz = ' . $e::class . "\n";
}
try {
        $b = BCMath::add(null, '9');
} catch (\Throwable $e) {
  echo 'xxx = ' . $e::class . "\n";
}
    }

    /**
     * Produces all combinations of test values.
     *
     * @return array
     */
    public function generateTwoParams()
    {
        $r = [
            ['9', '9'],
            ['9.99', '9.99'],
            ['9.99', '9.99', 2],
            ['9.99', '9.00009'],
            ['9.99', '9.00009', 4],
            ['9.99', '9.00009', 6],
            ['9.99', '-7', 6],
            ['9.99', '-7.2', 6],
            ['-9.99', '-3', 4],
            ['-9.99', '3.7', 4],
            ['-9.99', '-2.4', 5],
            ['0', '34'],
            ['0.15', '0.15', 1],
            ['0.15', '-0.1', 1],
            ['12', '19', 5],
            ['19', '12', 5],
            ['190', '2', 3],
            ['2', '190', 3],
            ['9', '0'],
            ['0', '9'],
            //[null, '9'],
            ['-0.0000005', '0', 3],
            /*
               there is some wonkyness with bcmul() in PHP 7.3 that this shim doesn't emulate:
               https://bugs.php.net/78071
               ie. instead of 0.000 you get -0.000 or vice versa. once a non-zero digit appears
                   the outputs become consistent
            */
            //['-0.0000005', '0.0000001', 3],
            ['-0', '0'],
            ['-0', '-0', 4]
        ];
        return $r;
    }

    /**
     * @dataProvider generateTwoParams
     */
    public function testAdd(...$params)
    {
        $a = bcadd(...$params);
        $b = BCMath::add(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider generateTwoParams
     */
    public function testSub(...$params)
    {
        $a = bcsub(...$params);
        $b = BCMath::sub(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider generateTwoParams
     * @requires PHP 7.3
     */
    public function testMul(...$params)
    {
        $a = bcmul(...$params);
        $b = BCMath::mul(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider generateTwoParams
     */
    public function testDiv(...$params)
    {
        if ($params[1] === '0' || $params[1] === '-0') {
            if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
                $this->setExpectedException('DivisionByZeroError');
            } else {
                $this->markTestSkipped('< PHP 8.0.0 has different behavior than >= PHP 8.0.0');
            }
        }

        $a = bcdiv(...$params);
        $b = BCMath::div(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider generateTwoParams
     * @requires PHP 7.2
     */
    public function testMod(...$params)
    {
        if ($params[1] === '0' || $params[1] === '-0') {
            if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
                $this->setExpectedException('DivisionByZeroError');
            } else {
                $this->markTestSkipped('< PHP 8.0.0 has different behavior than >= PHP 8.0.0');
            }
        }

        $a = bcmod(...$params);
        $b = BCMath::mod(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * Produces all combinations of test values.
     *
     * @return array
     */
    public function generatePowParams()
    {
        return [
            ['9', '9'],
            ['-9', '9'],
            ['9.99', '9'],
            ['9.99', '9', 4],
            ['9.99', '9', 6],
            ['9.99', '-7', 6],
            ['0', '34'],
            ['12', '19', 5],
            ['10', '-2', 10],
            ['-9.99', '-3', 10],
            ['0.15', '15', 10],
            ['0.15', '-1', 10],
            ['5', '0', 4]
        ];
    }

    /**
     * @dataProvider generatePowParams
     * @requires PHP 7.3
     */
    public function testPow(...$params)
    {
        $a = bcpow(...$params);
        $b = BCMath::pow(...$params);
        $this->assertSame($a, $b);
    }

    /**
     * Produces all combinations of test values.
     *
     * @return array
     */
    public function generatePowModParams()
    {
        return [
            ['9', '9', '17'],
            ['999', '999', '111', 5],
            ['-9', '1024', '123'],
            ['3', '1024', '-149'],
            ['2', '12', '2', 5],
            ['3', '0', '13'],
            ['-3', '0', '13', 4],
        ];

        if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
            $a = array_merge($a, [['9', '-1024', '127', 5]]);
        }

        return $a;
    }

    /**
     * @dataProvider generatePowModParams
     * @requires PHP 7.3
     */
    public function testPowMod(...$params)
    {
        $a = bcpowmod(...$params);
        $b = BCMath::powmod(...$params);
        $this->assertSame($a, $b);
    }

    public function testSqrt()
    {
        $a = bcsqrt('152.2756', 4);
        $b = BCMath::sqrt('152.2756', 4);
        $this->assertSame($a, $b);

        $a = bcsqrt('40000');
        $b = BCMath::sqrt('40000');
        $this->assertSame($a, $b);

        $a = bcsqrt('2', 4);
        $b = BCMath::sqrt('2', 4);
        $this->assertSame($a, $b);
    }

    public function testBoolScale()
    {
        $a = bcadd('5', '2', false);
        $b = BCMath::add('5', '2', false);
        $this->assertSame($a, $b);
    }

    public function testIntParam()
    {
        $a = bccomp('9223372036854775807', 16);
        $b = BCMath::comp('9223372036854775807', 16);
        $this->assertSame($a, $b);
    }

    public function setExpectedException($name, $message = null, $code = null)
    {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            parent::setExpectedException($name, $message, $code);
            return;
        }
        switch ($name) {
            case 'PHPUnit_Framework_Error_Notice':
            case 'PHPUnit_Framework_Error_Warning':
            case 'PHPUnit_Framework_Error_Deprecated':
                $name = str_replace('_', '\\', $name);
        }
        $this->expectException($name);
        if (!empty($message)) {
            $this->expectExceptionMessage($message);
        }
        if (!empty($code)) {
            $this->expectExceptionCode($code);
        }
    }
}