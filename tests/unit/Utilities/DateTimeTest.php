<?php

namespace Charm\Tests\Unit\Utilities;

use Charm\Utilities\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    public function testTimezoneChange()
    {
        $timezone = 'America/Chicago';

        $dateTime = DateTime::init('2023-01-10 03:42:00');
        $actualTimezone = $dateTime->as($timezone)->format('e');

        $this->assertSame($timezone, $actualTimezone);
    }

    public function testDisplayFormat()
    {
        $dateTime = DateTime::init('2023-01-10 03:42:00');
        $actualFormat = $dateTime
            ->as('America/Chicago')
            ->format('F j, Y \a\t g:i a');

        $this->assertSame('January 9, 2023 at 9:42 pm', $actualFormat);
    }

    public function testDatabaseFormat()
    {
        $wpGmtDateTime = '2023-01-10 03:42:00';
        $dateTime = DateTime::init($wpGmtDateTime);

        $this->assertSame($wpGmtDateTime, $dateTime->formatForDb());
    }

    public function testStringCast()
    {
        $wpGmtDateTime = '2023-01-10 03:42:00';
        $dateTime = DateTime::init($wpGmtDateTime);

        $this->assertSame(
            'January 10, 2023 at 3:42 am', (string) $dateTime
        );
    }
}
