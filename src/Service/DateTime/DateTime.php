<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
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
