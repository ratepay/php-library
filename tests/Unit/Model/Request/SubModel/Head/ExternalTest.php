<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
