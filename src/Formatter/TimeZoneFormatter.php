<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use DateTimeInterface;
use DateTimeZone;
use IntlCalendar;
use IntlTimeZone;
use function in_array;

class TimeZoneFormatter implements FormatterInterface
{
    private const TYPE_SPECIFIER_ID = 'timeseries_id';
    private const TYPE_SPECIFIER_LONG_NAME = 'timeseries_name';
    private const TYPE_SPECIFIER_SHORT_NAME = 'timeseries_short';

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
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        $intlCalendar = $this->createIntlCalendar();

        if ($value instanceof DateTimeInterface) {
            $intlCalendar->setTime($value->getTimestamp() * 1000);
            $value = $value->getTimezone();
        }

        if (!($value instanceof IntlTimeZone) && !($value instanceof DateTimeZone)) {
            throw InvalidValueException::invalidValueType($value, [DateTimeInterface::class, DateTimeZone::class, IntlTimeZone::class]);
        }

        $intlCalendar->setTimeZone($value);
        $inDaylight = $intlCalendar->inDaylightTime();
        $value = $intlCalendar->getTimeZone();

        $timeZoneMetaData = [
            self::TYPE_SPECIFIER_ID => $value->getID(),
            self::TYPE_SPECIFIER_LONG_NAME => $value->getDisplayName($inDaylight, IntlTimeZone::DISPLAY_LONG, $this->locale),
            self::TYPE_SPECIFIER_SHORT_NAME => $value->getDisplayName($inDaylight, IntlTimeZone::DISPLAY_SHORT, $this->locale),
        ];

        return $timeZoneMetaData[$typeSpecifier];
    }

    /**
     * @inheritdoc
     */
    public function has(string $typeSpecifier) : bool
    {
        $typeSpecifiers = [
            self::TYPE_SPECIFIER_ID,
            self::TYPE_SPECIFIER_LONG_NAME,
            self::TYPE_SPECIFIER_SHORT_NAME,
        ];

        return in_array($typeSpecifier, $typeSpecifiers, true);
    }

    /**
     * @return IntlCalendar
     */
    private function createIntlCalendar() : IntlCalendar
    {
        return IntlCalendar::createInstance(null, $this->locale);
    }
}