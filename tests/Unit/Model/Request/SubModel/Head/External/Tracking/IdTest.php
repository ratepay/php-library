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

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Head\External;

use PHPUnit\Framework\TestCase;
use RatePAY\Model\Request\SubModel\Head\External\Tracking;
use RatePAY\Model\Request\SubModel\Head\External\Tracking\Id;
use RatePAY\ModelBuilder;

/**
 * @requires PHPUnit 7.5
 */
class IdTest extends TestCase
{
    public function testSingleTrackingIdFromArray()
    {
        $modelBuilder = new ModelBuilder('id');
        $modelBuilder->setArray([
            'Description' => 'TEST',
            'Provider' => 'DHL'
        ]);

        /** @var Id $id */
        $id = $modelBuilder->getModel();

        self::assertEquals('TEST', $id->getId());
        self::assertEquals('TEST', $id->getDescription());
        self::assertEquals('DHL', $id->getProvider());
    }

    public function testToArray()
    {
        $id = (new Id())->setId('XXX')->setProvider('OOO');

        self::assertEquals([
            // this is a little bit tricky:
            // the cdata will be not written on the root-element cause to `toArray`-method got called directly .
            // if it is called in an parent model, the cdata element got written into the root-element (what is correct)
            // this is a "bug" in the toArray method of the AbstractModel
            // so we need to test the function in a separate method: @see `testTrackingToArray`
            'description' => [
                'cdata' => 'XXX',
            ],
            'attributes' => ['provider' => ['value' => 'OOO']],
        ], $id->toArray());
    }

    public function testTrackingToArray()
    {
        $tracking = new Tracking();
        $tracking->addId((new Id())->setId('XXX')->setProvider('OOO'));

        self::assertEquals([
            'id' => [
                [
                    'cdata' => 'XXX',
                    'attributes' => ['provider' => ['value' => 'OOO']],
                ]
            ]
        ], $tracking->toArray());
    }
}
