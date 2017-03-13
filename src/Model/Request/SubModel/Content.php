<?php

    namespace RatePAY\Model\Request\SubModel;

    class Content extends AbstractModel
    {

        /**
         * List of admitted fields.
         * Each field is public accessible by certain getter and setter.
         * E.g:
         * Set payment currency by using setCurrency(var). Get currency by using getCurrency(). (Please consider the camel case)
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
                'instanceOf' => __NAMESPACE__ . "\\Content\\Customer"
            ],
            'ShoppingBasket' => [
                'mandatory' => false,
                'instanceOf' => __NAMESPACE__ . "\\Content\\ShoppingBasket"
            ],
            'Payment' => [
                'mandatory' => false,
                'instanceOf' => __NAMESPACE__ . "\\Content\\Payment"
            ],
            'Invoicing' => [
                'mandatory' => false,
                'instanceOf' => __NAMESPACE__ . "\\Content\\Invoicing"
            ],
            'InstallmentCalculation' => [
                'mandatory' => false,
                'instanceOf' => __NAMESPACE__ . "\\Content\\InstallmentCalculation"
            ],
            'Additional' => [
                'mandatory' => false,
                'instanceOf' => __NAMESPACE__ . "\\Content\\Additional"
            ]
        ];

    }
