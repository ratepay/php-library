<?php

    namespace RatePAY\Model\Request\SubModel\Head;

    use RatePAY\Model\Request\SubModel\AbstractModel;

    class Meta extends AbstractModel
    {

        /**
         * List of admitted fields.
         * Each field is public accessible by certain getter and setter.
         * E.g:
         * Set systems by using setSystems(var). Get systems by using getSystems(). (Please consider the camel case)
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
            'Systems' => [
                'mandatory' => true,
                'instanceOf' => "Head\\Meta\\Systems"
            ],
        ];

        /**
         * Manipulates the parent method to set instance of systems if not already set
         *
         * @return array
         */
        public function toArray()
        {
            if (!key_exists('value', $this->admittedFields['Systems'])) {
                $prototype = $this->admittedFields['Systems']['instanceOf'];
                $this->admittedFields['Systems']['value'] = new $prototype;
            }

            return parent::toArray();
        }

    }
