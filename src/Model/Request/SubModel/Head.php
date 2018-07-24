<?php

    namespace RatePAY\Model\Request\SubModel;

    class Head extends AbstractModel
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
            'SystemId' => [
                'mandatory' => true,
                'cdata' => true
            ],
            'TransactionId' => [
                'mandatory' => false,
            ],
            'Operation' => [
                'mandatory' => true,
            ],
                'Subtype' => [
                    'mandatory' => false,
                    'isAttributeTo' => "Operation"
                ],
            'Credential' => [
                'mandatory' => true,
                'instanceOf' => "Head\\Credential"
            ],
            'External' => [
                'mandatory' => false,
                'instanceOf' => "Head\\External"
            ],
            'CustomerDevice' => [
                'mandatory' => false,
                'instanceOf' => "Head\\CustomerDevice"
            ],
            'Meta' => [
                'mandatory' => true,
                'instanceOf' => "Head\\Meta"
            ],
        ];

        /**
         * Manipulates the parent method to set instance of meta if not already set
         *
         * @return array
         */
        public function toArray()
        {
            if (!key_exists('value', $this->admittedFields['Meta'])) {
                $prototype = $this->admittedFields['Meta']['instanceOf'];
                $this->admittedFields['Meta']['value'] = new $prototype;
            }

            return parent::toArray();
        }

        /**
         * Is transaction id set
         *
         * @return bool
         */
        public function isTransactionIdSet() {
            return (key_exists('value', $this->admittedFields['TransactionId']));
        }

        /**
         * Returns whether subtype is set
         *
         * @return bool
         */
        public function isSubtypeSet()
        {
            return (key_exists('value', $this->admittedFields['Subtype']));
        }

    }
