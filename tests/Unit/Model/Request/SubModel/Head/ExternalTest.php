<?php
/*
 * RatePAY php-library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Head;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Head\External;
use RatePAY\Model\Request\SubModel\Head\External\Tracking;
use RatePAY\Model\Request\SubModel\Head\External\Tracking\Id;

/**
 * @requires PHPUnit 7.5
 */
class ExternalTest extends TestCase
{
    public function testTracking()
    {
        $external = new External();
        $external->setTracking(
            (new Tracking())->addId((new Id())->setId('TEST'))
        );

        self::assertArrayHasKey('tracking', $external->toArray());
        self::assertIsArray($external->toArray()['tracking']);
    }

    public function testTrackingToArray()
    {
        $external = new External();
        $external->setTracking(
            (new Tracking())->addId((new Id())->setId('TEST'))
        );

        self::assertArrayHasKey('tracking', $external->toArray());
        self::assertIsArray($external->toArray()['tracking']);
    }
}
