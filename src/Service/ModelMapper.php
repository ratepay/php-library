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

namespace RatePAY\Service;

class ModelMapper
{
    private static $commonPathModel = 'RatePAY\\Model\\';

    private static $requestModels = [
        'PaymentInit' => 'PaymentInit',
        'PaymentQuery' => 'PaymentQuery',
        'PaymentRequest' => 'PaymentRequest',
        'CalculationRequest' => 'CalculationRequest',
        'ConfigurationRequest' => 'ConfigurationRequest',
        'ConfirmationDeliver' => 'ConfirmationDeliver',
        'PaymentChange' => 'PaymentChange',
        'PaymentConfirm' => 'PaymentConfirm',
        'ProfileRequest' => 'ProfileRequest',
    ];

    private static $subModelNamePathMapping = [
        'Head' => 'Head',
            'Credential' => 'Head\\Credential',
            'CustomerDevice' => 'Head\\CustomerDevice',
            'External' => 'Head\\External',
                'Tracking' => 'Head\\External\\Tracking',
                    'Id' => 'Head\\External\\Tracking\\Id',
            'Meta' => 'Head\\Meta',
                'Systems' => 'Head\\Meta\\Systems',
                    'System' => 'Head\\Meta\\Systems\\System',
        'Content' => 'Content',
            'Additional' => 'Content\\Additional',
            'ShoppingBasket' => 'Content\\ShoppingBasket',
                'Items' => 'Content\\ShoppingBasket\\Items',
                    'Item' => 'Content\\ShoppingBasket\\Items\\Item',
                'Shipping' => 'Content\\ShoppingBasket\\Shipping',
                'Discount' => 'Content\\ShoppingBasket\\Discount',
            'Customer' => 'Content\\Customer',
                'BankAccount' => 'Content\\Customer\\BankAccount',
                'Contacts' => 'Content\\Customer\\Contacts',
                    'Phone' => 'Content\\Customer\\Contacts\\Phone',
                    'Fax' => 'Content\\Customer\\Contacts\\Phone',
                    'Mobile' => 'Content\\Customer\\Contacts\\Phone',
                'Addresses' => 'Content\\Customer\\Addresses',
                    'Address' => 'Content\\Customer\\Addresses\\Address',
            'InstallmentCalculation' => 'Content\\InstallmentCalculation',
                'CalculationRate' => 'Content\\InstallmentCalculation\\CalculationRate',
                'CalculationTime' => 'Content\\InstallmentCalculation\\CalculationTime',
                'Configuration' => 'Content\\InstallmentCalculation\\Configuration',
            'Payment' => 'Content\\Payment',
                'InstallmentDetails' => 'Content\\Payment\\InstallmentDetails',
            'Invoicing' => 'Content\\Invoicing',
    ];

    public static function getFullPathRequestModel($requestedModel)
    {
        return self::$commonPathModel . 'Request\\' . $requestedModel;
    }

    public static function getFullPathRequestSubModel($requestedModel)
    {
        return self::$commonPathModel . 'Request\\' . 'SubModel\\' . self::$subModelNamePathMapping[$requestedModel];
    }

    public static function getFullPathResponseModel($requestedModel)
    {
        return self::$commonPathModel . 'Response\\' . $requestedModel;
    }

    public static function getFullPathResponseSubModel($requestedModel)
    {
        return self::$commonPathModel . 'Response\\' . 'SubModel\\' . self::$subModelNamePathMapping[$requestedModel];
    }

    public static function getRequestModels()
    {
        return self::$requestModels;
    }
}
