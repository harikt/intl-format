<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Formatter\SprintfFormatter;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;
use PHPUnit\Framework\TestCase;

class ImplementationTest extends TestCase
{
    /**
     * @dataProvider formattingWorksProvider
     */
    public function testFormattingWorks($expected, $message, ...$args) : void
    {
        $formatter = [
            new SprintfFormatter(),
        ];

        $intlFormat = new IntlFormat($formatter, new SprintfParser());

        self::assertSame($expected, $intlFormat->format($message, ...$args));
    }

    /**
     * @return mixed[][]
     */
    public function formattingWorksProvider() : array
    {
        return [
            ['there are 12 monkeys on the 002 trees', 'there are %d %s on the %03d trees', 12, 'monkeys', 2],
        ];
    }
}
