<?php

/*
 * RatePAY PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Request\SubModel;

use RatePAY\Model\Request\SubModel\Content\Additional;
use RatePAY\Model\Request\SubModel\Content\Customer;
use RatePAY\Model\Request\SubModel\Content\InstallmentCalculation;
use RatePAY\Model\Request\SubModel\Content\Invoicing;
use RatePAY\Model\Request\SubModel\Content\Payment;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket;

/**
 * @method $this                  setCustomer(Customer $customer)
 * @method Customer               getCustomer()
 * @method $this                  setShoppingBasket(ShoppingBasket $shoppingBasket)
 * @method ShoppingBasket         getShoppingBasket()
 * @method $this                  setPayment(Payment $payment)
 * @method Payment                getPayment()
 * @method $this                  setInvoicing(Invoicing $invoicing)
 * @method Invoicing              getInvoicing()
 * @method $this                  setInstallmentCalculation(InstallmentCalculation $installmentCalculation)
 * @method InstallmentCalculation getInstallmentCalculation()
 * @method $this                  setAdditional(Additional $additional)
 * @method Additional             getAdditional()
 */
class Content extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment currency by using setCurrency(var). Get currency by using getCurrency(). (Please consider the camel case).
     *
     * Settings:
     * mandatory            = field is mandatory (or optional)
     * mandatoryByRule      = field is mandatory if rule is passed
     * optionalByRule       = field will only returned if rule is passed
     * default              = default value if no different value is set
     * isAttribute          = field is xml attribute to parent object
     * isAttributeTo        = field is xml attribute to field (in value)
     * instanceOf           = value has to be an instance of class (in value)
     * cdata                = value will be wrapped in CDATA tag
     *
     * @var array
     */
    public $admittedFields = [
        'Customer' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\Customer',
        ],
        'ShoppingBasket' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\ShoppingBasket',
        ],
        'Payment' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\Payment',
        ],
        'Invoicing' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\Invoicing',
        ],
        'InstallmentCalculation' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\InstallmentCalculation',
        ],
        'Additional' => [
            'mandatory' => false,
            'instanceOf' => 'Content\\Additional',
        ],
    ];
}
