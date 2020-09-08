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

namespace RatePAY\Model\Request\SubModel\Content\ShoppingBasket;

use RatePAY\Model\Request\SubModel\AbstractModel;
use RatePAY\Model\Request\SubModel\Content\ShoppingBasket\Items\Item;

/**
 * @method $this  addItem(Item $item)
 * @method Item[] getItems()
 */
class Items extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set payment unit price gross by using setUnitPriceGross(var). Get unit price gross by using getUnitPriceGross(). (Please consider the camel case).
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
        'Item' => [
            'mandatoryByRule' => true,
            'instanceOf' => 'Content\\ShoppingBasket\\Items\\Item',
            'multiple' => true,
        ],
    ];

    /**
     * Item article number rule : identical article numbers on different items are not allowed.
     *
     * @return bool
     */
    protected function rule()
    {
        $articleNumbers = [];

        if (key_exists('value', $this->admittedFields['Item'])) {
            foreach ($this->admittedFields['Item']['value'] as $item) {
                $articleNumber = $item->admittedFields['ArticleNumber']['value'];
                if (key_exists('value', $item->admittedFields['UniqueArticleNumber'])) {
                    $articleNumber .= $item->admittedFields['UniqueArticleNumber']['value'];
                }

                if (in_array($articleNumber, $articleNumbers)) {
                    $this->setErrorMsg('Identical article numbers on different items are not allowed. Please specify with UniqueArticleNumber.');

                    return false;
                } else {
                    $articleNumbers[] = $articleNumber;
                }
            }
        }

        return true;
    }
}
