<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Service\DateTime\DateTime;

class DateTimeTest extends TestCase
{
    /** @dataProvider provideFakeNow */
    public function testNow($now, $expectedDate, $format)
    {
        DateTime::withTestingNow(new DateTime($now));

        $dateTime = DateTime::now();

        DateTime::resetTestingNow();

        $this->assertEquals($expectedDate, $dateTime->format($format));
    }

    public function provideFakeNow()
    {
        return [
            ['2020-01-10T10:32:57+01:00', '2020-01-10', 'Y-m-d'],
            ['2018-05-10T17:32:23+00:00', '2018-05-10 17:32:23', 'Y-m-d H:i:s'],
            ['yesterday', (new \DateTime('yesterday'))->format('Y-m-d'), 'Y-m-d'],
        ];
    }

    /** @dataProvider provideFakeToday */
    public function testToday($now, $expectedDate, $format)
    {
        DateTime::withTestingNow(new DateTime($now));

        $dateTime = DateTime::today();

        DateTime::resetTestingNow();

        $this->assertEquals($expectedDate, $dateTime->format($format));
    }

    public function provideFakeToday()
    {
        return [
            ['2020-01-10T10:32:57+01:00', '2020-01-10', 'Y-m-d'],
            ['2018-05-10T17:32:23+00:00', '2018-05-10 00:00:00', 'Y-m-d H:i:s'],
            ['yesterday', (new \DateTime('yesterday'))->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'],
        ];
    }

    /** @dataProvider provideAddDayTransformations */
    public function testAddDayTransformation($now, $days, $expectedDate)
    {
        DateTime::withTestingNow(new DateTime($now));

        $dateTime = DateTime::now()->addDays($days);

        DateTime::resetTestingNow();

        $this->assertEquals($expectedDate, $dateTime->format('Y-m-d H:i:s'));
    }

    public function provideAddDayTransformations()
    {
        return [
            ['2020-01-10T10:32:57+01:00', 5, '2020-01-15 10:32:57'],
            ['2020-02-27T17:32:23+00:00', 3, '2020-03-01 17:32:23'],
            ['2019-02-27T17:32:23+00:00', 3, '2019-03-02 17:32:23'],
            ['2019-02-27T17:32:23+00:00', -3, '2019-02-24 17:32:23'],
        ];
    }

    /** @dataProvider provideAddMonthTransformations */
    public function testAddMonthTransformation($now, $months, $expectedDate)
    {
        DateTime::withTestingNow(new DateTime($now));

        $dateTime = DateTime::now()->addMonths($months);

        DateTime::resetTestingNow();

        $this->assertEquals($expectedDate, $dateTime->format('Y-m-d H:i:s'));
    }

    public function provideAddMonthTransformations()
    {
        return [
            ['2020-01-10T10:32:57+01:00', 5, '2020-06-10 10:32:57'],
            ['2020-11-27T17:32:23+00:00', 3, '2021-02-27 17:32:23'],
            ['2019-11-27T17:32:23+00:00', -3, '2019-08-27 17:32:23'],
            ['2019-02-27T17:32:23+00:00', -3, '2018-11-27 17:32:23'],
        ];
    }
}
