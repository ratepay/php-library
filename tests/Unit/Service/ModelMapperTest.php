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

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Service\ModelMapper;

class ModelMapperTest extends TestCase
{
    /** @dataProvider provideRequestModels */
    public function testGetFullPathRequestModel($model, $expectedFqcn)
    {
        $fqcn = ModelMapper::getFullPathRequestModel($model);

        $this->assertEquals($expectedFqcn, $fqcn);
    }

    public function provideRequestModels()
    {
        return [
            ["PaymentInit", \RatePAY\Model\Request\PaymentInit::class],
            ["PaymentQuery", \RatePAY\Model\Request\PaymentQuery::class],
            ["PaymentRequest", \RatePAY\Model\Request\PaymentRequest::class],
            ["CalculationRequest", \RatePAY\Model\Request\CalculationRequest::class],
            ["ConfigurationRequest", \RatePAY\Model\Request\ConfigurationRequest::class],
            ["ConfirmationDeliver", \RatePAY\Model\Request\ConfirmationDeliver::class],
            ["PaymentChange", \RatePAY\Model\Request\PaymentChange::class],
            ["PaymentConfirm", \RatePAY\Model\Request\PaymentConfirm::class],
            ["ProfileRequest", \RatePAY\Model\Request\ProfileRequest::class],
        ];
    }

    /** @dataProvider provideRequestSubModels */
    public function testGetFullPathRequestSubModel($model, $expectedFqcn)
    {
        $fqcn = ModelMapper::getFullPathRequestSubModel($model);

        $this->assertEquals($expectedFqcn, $fqcn);
    }

    public function provideRequestSubModels()
    {
        return [
            ['Head', \RatePAY\Model\Request\SubModel\Head::class],
            ['Credential', \RatePAY\Model\Request\SubModel\Head\Credential::class],
            ['CustomerDevice', \RatePAY\Model\Request\SubModel\Head\CustomerDevice::class],
            ['External', \RatePAY\Model\Request\SubModel\Head\External::class],
            ['Tracking', \RatePAY\Model\Request\SubModel\Head\External\Tracking::class],
            ['Meta', \RatePAY\Model\Request\SubModel\Head\Meta::class],
            ['Systems', \RatePAY\Model\Request\SubModel\Head\Meta\Systems::class],
            ['System', \RatePAY\Model\Request\SubModel\Head\Meta\Systems\System::class],
            ['Content', \RatePAY\Model\Request\SubModel\Content::class],
            ['Additional', \RatePAY\Model\Request\SubModel\Content\Additional::class],
            ['ShoppingBasket', \RatePAY\Model\Request\SubModel\Content\ShoppingBasket::class],
            ['Items', \RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items::class],
            ['Item', \RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items\Item::class],
            ['Shipping', \RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Shipping::class],
            ['Discount', \RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Discount::class],
            ['Customer', \RatePAY\Model\Request\SubModel\Content\Customer::class],
            ['BankAccount', \RatePAY\Model\Request\SubModel\Content\Customer\BankAccount::class],
            ['Contacts', \RatePAY\Model\Request\SubModel\Content\Customer\Contacts::class],
            ['Phone', \RatePAY\Model\Request\SubModel\Content\Customer\Contacts\Phone::class],
            ['Addresses', \RatePAY\Model\Request\SubModel\Content\Customer\Addresses::class],
            ['Address', \RatePAY\Model\Request\SubModel\Content\Customer\Addresses\Address::class],
            ['InstallmentCalculation', \RatePAY\Model\Request\SubModel\Content\InstallmentCalculation::class],
            ['CalculationRate', \RatePAY\Model\Request\SubModel\Content\InstallmentCalculation\CalculationRate::class],
            ['CalculationTime', \RatePAY\Model\Request\SubModel\Content\InstallmentCalculation\CalculationTime::class],
            ['Configuration', \RatePAY\Model\Request\SubModel\Content\InstallmentCalculation\Configuration::class],
            ['Payment', \RatePAY\Model\Request\SubModel\Content\Payment::class],
            ['InstallmentDetails', \RatePAY\Model\Request\SubModel\Content\Payment\InstallmentDetails::class],
            ['Invoicing', \RatePAY\Model\Request\SubModel\Content\Invoicing::class],
        ];
    }

    /** @dataProvider provideResponseModels */
    public function testGetFullPathResponseModel($model, $expectedFqcn)
    {
        $fqcn = ModelMapper::getFullPathResponseModel($model);

        $this->assertEquals($expectedFqcn, $fqcn);
    }

    public function provideResponseModels()
    {
        return [
            ['CalculationRequest', \RatePAY\Model\Response\CalculationRequest::class],
            ['ConfigurationRequest', \RatePAY\Model\Response\ConfigurationRequest::class],
            ['ConfirmationDeliver', \RatePAY\Model\Response\ConfirmationDeliver::class],
            ['PaymentChange', \RatePAY\Model\Response\PaymentChange::class],
            ['PaymentConfirm', \RatePAY\Model\Response\PaymentConfirm::class],
            ['PaymentInit', \RatePAY\Model\Response\PaymentInit::class],
            ['PaymentQuery', \RatePAY\Model\Response\PaymentQuery::class],
            ['PaymentRequest', \RatePAY\Model\Response\PaymentRequest::class],
            ['ProfileRequest', \RatePAY\Model\Response\ProfileRequest::class],
        ];
    }
}
