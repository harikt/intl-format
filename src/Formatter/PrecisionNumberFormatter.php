<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use MessageFormatter as Message;
use function is_numeric;
use function preg_match;
use function sprintf;
use function str_repeat;

class PrecisionNumberFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private static $matchPattern = '/^\.([0-9]+)number$/';

    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        if (!is_numeric($value)) {
            throw InvalidValueException::invalidValueType($value, ['integer', 'double']);
        }

        preg_match(self::$matchPattern, $typeSpecifier, $matches);

        $fractionalDigits = str_repeat('0', (int) $matches[1]);

        return (string) Message::formatMessage(
            $this->locale,
            sprintf('{0,number,#,##0.%s}', $fractionalDigits),
            [$value]
        );
    }

    /**
     * @inheritDoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return 1 === preg_match(self::$matchPattern, $typeSpecifier);
    }
}