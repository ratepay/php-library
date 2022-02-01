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

trait DateTimeTesting
{
    /**
     * @var DateTime
     */
    private static $_testingNow;

    /**
     * It sets a custom DateTime instance as the "now" value.
     * This handy hook allows performing tests without side effects.
     *
     * @return DateTime
     */
    public static function withTestingNow(DateTime $dateTime)
    {
        self::$_testingNow = $dateTime;

        return self::now();
    }

    /**
     * It restores testing now to empty value and allows normal DateTime functionality.
     *
     * @return void
     */
    public static function resetTestingNow()
    {
        self::$_testingNow = null;
    }

    /**
     * @return bool
     */
    private static function hasTestingNow()
    {
        return isset(self::$_testingNow);
    }

    /**
     * @return DateTime
     */
    private static function getTestingNow()
    {
        return clone self::$_testingNow;
    }
}
