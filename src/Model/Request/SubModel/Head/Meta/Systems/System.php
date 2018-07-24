<?php

    namespace RatePAY\Model\Request\SubModel\Head\Meta\Systems;

    use RatePAY\Model\Request\SubModel\AbstractModel;
    use RatePAY\Model\Request\SubModel\Constants as CONSTANTS;

    class System extends AbstractModel
    {

        /**
         * List of admitted fields.
         * Each field is public accessible by certain getter and setter.
         * E.g:
         * Set payment profile id by using setProfileId(var). Get profile id by using getProfileId(). (Please consider the camel case)
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
            'Name' => [
                'mandatory' => true,
                'isAttribute' => true,
                'default' => CONSTANTS::LIBRARY_SYSTEM_NAME,
            ],
            'Version' => [
                'mandatory' => true,
                'isAttribute' => true,
                'default' => CONSTANTS::LIBRARY_VERSION
            ],
        ];

        /**
         * Manipulates the parent method to merge set system name and version with default
         *
         * @return array
         * @throws \RatePAY\Exception\ModelException
         * @throws \RatePAY\Exception\RuleSetException
         */
        public function toArray()
        {
            if (key_exists('value', $this->admittedFields['Name'])) {
                $this->admittedFields['Name']['value'] .= "/" . $this->admittedFields['Name']['default'];
            }

            if (key_exists('value', $this->admittedFields['Version'])) {
                $this->admittedFields['Version']['value'] .= "/" . $this->admittedFields['Version']['default'];
            }

            return parent::toArray();
        }

    }
