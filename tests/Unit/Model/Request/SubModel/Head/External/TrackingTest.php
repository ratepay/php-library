<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Head\External;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Head\External\Tracking;
use RatePAY\Model\Request\SubModel\Head\External\Tracking\Id;
use RatePAY\ModelBuilder;

/**
 * @requires PHPUnit 7.5
 */
class TrackingTest extends TestCase
{
    public function testSingleFromArray()
    {
        $modelBuilder = new ModelBuilder('tracking');
        $modelBuilder->setArray([
            [
                'Id' => [
                    'Description' => 'Test',
                ],
            ],
        ]);
        /** @var Tracking $tracking */
        $tracking = $modelBuilder->getModel();

        self::assertCount(1, $tracking->getIds());
        self::assertInstanceOf(Id::class, $tracking->getIds()[0]);
    }

    public function testMultipleFromArray()
    {
        $modelBuilder = new ModelBuilder('tracking');
        $modelBuilder->setArray([
            [
                'Id' => [
                    'Description' => 'Test',
                ],
            ],
            [
                'Id' => [
                    'Description' => 'Test',
                ],
            ],
        ]);
        /** @var Tracking $tracking */
        $tracking = $modelBuilder->getModel();

        self::assertCount(2, $tracking->getIds());
        self::assertInstanceOf(Id::class, $tracking->getIds()[0]);
        self::assertInstanceOf(Id::class, $tracking->getIds()[1]);
    }

    public function testSingleToArray()
    {
        $modelBuilder = new ModelBuilder('tracking');
        $modelBuilder->setArray([
            [
                'Id' => [
                    'Description' => 'Test',
                ],
            ],
        ]);
        /** @var Tracking $tracking */
        $tracking = $modelBuilder->getModel();

        self::assertArrayHasKey('id', $tracking->toArray());
        self::assertIsArray($tracking->toArray()['id']);
        self::assertCount(1, $tracking->toArray()['id']);
    }

    public function testSingleModel()
    {
        $tracking = new Tracking();
        $tracking->addId((new Id('XXX'))->setProvider('OOO'));

        self::assertIsArray($tracking->getIds());
        self::assertCount(1, $tracking->getIds());
        self::assertInstanceOf(Id::class, $tracking->getIds()[0]);

        // deprecation tests:
        self::assertEquals($tracking->getId(), $tracking->getIds()[0]->getId());
        self::assertEquals($tracking->getProvider(), $tracking->getIds()[0]->getProvider());
    }

    public function testMultipleModel()
    {
        $tracking = new Tracking();
        $tracking->addId((new Id('XXX'))->setProvider('OOO'));
        $tracking->addId((new Id('III'))->setProvider('UUU'));

        self::assertIsArray($tracking->getIds());
        self::assertCount(2, $tracking->getIds());
        self::assertInstanceOf(Id::class, $tracking->getIds()[0]);
        self::assertInstanceOf(Id::class, $tracking->getIds()[1]);
    }

    public function testMultipleToArray()
    {
        $modelBuilder = new ModelBuilder('tracking');
        $modelBuilder->setArray([
            [
                'Id' => [
                    'Description' => 'Test-1',
                ],
            ],
            [
                'Id' => [
                    'Description' => 'Test-2',
                ],
            ],
        ]);

        /** @var Tracking $tracking */
        $tracking = $modelBuilder->getModel();

        self::assertArrayHasKey('id', $tracking->toArray());
        self::assertIsArray($tracking->toArray()['id']);
        self::assertCount(2, $tracking->toArray()['id']);
    }

    public function testDepreactedSingleTrackingIdFromArray()
    {
        $modelBuilder = new ModelBuilder('tracking');
        $modelBuilder->setArray([
            'Id' => [
                'Description' => 'OOO',
                'Provider' => 'XXX'
            ],
        ]);
        /** @var Tracking $tracking */
        $tracking = $modelBuilder->getModel();

        $ids = $tracking->getIds();
        self::assertCount(1, $ids);
        self::assertInstanceOf(Id::class, $ids[0]);
        self::assertEquals('OOO', $ids[0]->getId());
        self::assertEquals('XXX', $ids[0]->getProvider());
    }

}
