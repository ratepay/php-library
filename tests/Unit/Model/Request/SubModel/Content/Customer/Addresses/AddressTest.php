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

namespace RatePAY\Tests\Unit\Model\Request\SubModel\Content\Customer\Addresses;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\ModelException;
use RatePAY\Exception\RuleSetException;
use RatePAY\Model\Request\SubModel\Content\Customer\Addresses\Address;

class AddressTest extends TestCase
{
    public function testErrorThrownIfDeliveryAddressHasNoNames()
    {
        $address = new Address();
        $address->setType('DELIVERY');

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Rule set exception : Delivery address requires firstname and lastname');

        $address->toArray();
    }

    public function testErrorThrownIfRegistryAddressHasCompany()
    {
        $address = new Address();
        $address->setType('REGISTRY');

        $this->expectException(RuleSetException::class);
        $this->expectExceptionMessage('Rule set exception : Registry address requires company');

        $address->toArray();
    }

    public function testErrorThrownIfStreetNotSet()
    {
        $address = new Address();
        $address->setType('DELIVERY');
        $address->setFirstName('Alice');
        $address->setLastName('Abernathy');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'Street\' is required');

        $address->toArray();
    }

    public function testErrorThrownIfZipCodeNotSet()
    {
        $address = new Address();
        $address->setType('DELIVERY');
        $address->setFirstName('Alice');
        $address->setLastName('Abernathy');
        $address->setStreet('Zombie Road');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'ZipCode\' is required');

        $address->toArray();
    }

    public function testErrorThrownIfCityNotSet()
    {
        $address = new Address();
        $address->setType('DELIVERY');
        $address->setFirstName('Alice');
        $address->setLastName('Abernathy');
        $address->setStreet('Zombie Road');
        $address->setZipCode('13452');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'City\' is required');

        $address->toArray();
    }

    public function testErrorThrownIfCountryCodeNotSet()
    {
        $address = new Address();
        $address->setType('DELIVERY');
        $address->setFirstName('Alice');
        $address->setLastName('Abernathy');
        $address->setStreet('Zombie Road');
        $address->setZipCode('13452');
        $address->setCity('Racoon City');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'CountryCode\' is required');

        $address->toArray();
    }
}
