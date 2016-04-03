<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\InvalidValueException;

class InvalidValueExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @expectedExceptionMessage Invalid type "string" of value. Allowed types: "integer, double".
     */
    public function testInvalidValueType()
    {
        throw InvalidValueException::invalidValueType('foo', ['integer', 'double']);
    }
}