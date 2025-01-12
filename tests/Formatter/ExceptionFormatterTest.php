<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\ExceptionFormatter;
use PHPUnit\Framework\TestCase;
use Throwable;

class ExceptionFormatterTest extends TestCase
{
    /**
     * @var ExceptionFormatter
     */
    private $formatter;

    protected function setUp() : void
    {
        parent::setUp();

        $this->formatter = new ExceptionFormatter();
    }

    /**
     * @dataProvider provideTypeSpecifier
     *
     * @param string $typeSpecifier
     */
    public function testHas(string $typeSpecifier) : void
    {
        self::assertTrue($this->formatter->has($typeSpecifier));
    }

    public function testHasIsFalse() : void
    {
        self::assertFalse($this->formatter->has('foo'));
    }

    /**
     * @dataProvider provideExceptions
     *
     * @param string $typeSpecifier
     * @param Throwable $value
     * @param mixed $expected
     */
    public function testFormatValue(string $typeSpecifier, Throwable $value, $expected) : void
    {
        self::assertSame($expected, $this->formatter->formatValue($typeSpecifier, $value));
    }

    public function testFormatValueInvalidValue() : void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(10);

        $this->formatter->formatValue('emessage', 1);
    }

    /**
     * @return string[][]
     */
    public function provideTypeSpecifier() : array
    {
        return [
            'emessage' => ['emessage'],
            'ecode' => ['ecode'],
            'efile' => ['efile'],
            'eline' => ['eline'],
            'etrace' => ['etrace'],
        ];
    }

    /**
     * @return array
     */
    public function provideExceptions()
    {
        $e = new \Exception('foo', 42);
        return [
            'emessage' => ['emessage', $e, $e->getMessage()],
            'ecode' => ['ecode', $e, (string) $e->getCode()],
            'efile' => ['efile', $e, $e->getFile()],
            'eline' => ['eline', $e, (string) $e->getLine()],
            'etrace' => ['etrace', $e, $e->getTraceAsString()],
        ];
    }
}
