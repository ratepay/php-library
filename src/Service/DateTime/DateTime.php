<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service\DateTime;

use DateTime as InternalDateTime;

/**
 * DateTime wrapper class inspired by Carbon.
 * It provides handy accessors and allow isolation of internal classes during testing.
 */
class DateTime extends InternalDateTime
{
    use DateTimeTesting;
    use DateTimeModifiers;
    use DateTimeCalculators;

    /**
     * @return DateTime
     */
    public static function now()
    {
        return self::hasTestingNow() ? self::getTestingNow() : new DateTime();
    }

    /**
     * @return DateTime
     */
    public static function today()
    {
        return self::hasTestingNow() ? self::getTestingNow()->setTime(0, 0) : new DateTime('today');
    }
}
