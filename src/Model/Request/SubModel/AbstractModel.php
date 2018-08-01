<?php

namespace RatePAY\Model\Request\SubModel;

use RatePAY\Service\Util;
use RatePAY\Exception\RequestException;
use RatePAY\Exception\RuleSetException;
use RatePAY\Exception\ModelException;

abstract class AbstractModel
{
    /**
     * List of admitted fields
     *
     * @var array
     */
    public $admittedFields = [];

    /**
     * Error message
     *
     * @var string
     */
    private $errorMsg = "";

    public function __construct()
    {
        array_walk($this->admittedFields, function (&$value) {
            if (key_exists('instanceOf', $value)) {
                $value['instanceOf'] = __NAMESPACE__ . "\\" . $value['instanceOf'];
            }
        });
    }

    /**
     * Collecting call function to validate action+field and split in getter and setter
     *
     * @param $name
     * @param $arguments
     * @return bool|null
     * @throws ModelException
     * @throws RequestException
     */
    public function __call($name, $arguments) {
        $action = substr($name, 0, 3);
        $field = substr($name, 3);

        if (!key_exists($field, $this->admittedFields) && (property_exists($this, "settings") && !key_exists($field, $this->settings))) {
            throw new RequestException("Field '" . $field . "' invalid");
        }

        if ($action == "set") {
            return $this->commonSetter($field, $arguments);
        } elseif ($action == "get") {
            return $this->commonGetter($field);
        } else {
            throw new RequestException("Action invalid");
        }


    }

    /**
     * Common getter
     *
     * @param $field
     * @param $arguments
     * @return $this
     * @throws ModelException
     */
    public function commonSetter($field, $arguments) {
        if (is_array($arguments) && $arguments[0] !== "") {
            if (property_exists($this, 'settings') && key_exists($field, $this->settings)) { // If it's a setting, save argument into settings
                // @ToDo: find a better structure
                $this->settings[$field] = $arguments[0];
                return $this;
            } elseif (!key_exists($field, $this->admittedFields)) {
                throw new ModelException("Invalid field '" . $field . "'");
            }

            if (key_exists('instanceOf', $this->admittedFields[$field]) && get_class($arguments[0]) != $this->admittedFields[$field]['instanceOf']) {
                throw new ModelException("Wrong class instance set to '" . $field . "' - '" . $this->admittedFields[$field]['instanceOf'] . "' required");
            }

            if (key_exists('multiple', $this->admittedFields[$field])) {
                $this->admittedFields[$field]['value'][] = $arguments[0];
            } else {
                $this->admittedFields[$field]['value'] = $arguments[0];
            }

        }
        return $this;
    }

    /**
     * Common getter
     *
     * @param $field
     * @return mixed
     */
    public function commonGetter($field) {
        if (property_exists($this, "settings") && key_exists($field, $this->settings)) {
            return $this->settings[$field];
        } elseif (key_exists("value", $this->admittedFields[$field])) {
            return $this->admittedFields[$field]['value'];
        }

        return null;
    }

    /**
     * Return all values as Array
     *
     * @return array
     * @throws ModelException
     * @throws RuleSetException
     */
    public function toArray()
    {
        // ToDo: Change admittedFields structure. Splitting current value in value and child (object).

        $return = [];

        foreach ($this->admittedFields as $fieldName => $fieldSettings) {
            $xmlField = Util::changeCamelCaseToDash($fieldName);
            $returnPush = false;

            if (key_exists('value', $fieldSettings) &&
                is_string($fieldSettings['value'])) {
                if (key_exists('uppercase', $fieldSettings) && $fieldSettings['uppercase'] === true) {
                    $fieldSettings['value'] = strtoupper($fieldSettings['value']);
                } elseif (key_exists('lowercase', $fieldSettings) && $fieldSettings['lowercase'] === true) {
                    $fieldSettings['value'] = strtolower($fieldSettings['value']);
                }
            }

            if (key_exists('mandatory', $fieldSettings) && $fieldSettings['mandatory'] === true) { // If field is mandatory
                if (key_exists('value', $fieldSettings)) {                                         // If value is not empty
                    $returnPush = true;
                } elseif (key_exists('default', $fieldSettings)) {                                 // If value is empty but default is defined
                    $fieldSettings['value'] = $fieldSettings['default'];
                    $returnPush = true;
                } else {
                    throw new ModelException("Field '" . $fieldName . "' is required");
                }
            } elseif(key_exists('mandatoryByRule', $fieldSettings)) {                              // If field is mandatory by rule
                if ($this->rule() === true) {                                                      // If rule is passed
                    if (key_exists('value', $fieldSettings)) {                                     // If value is not empty
                        $returnPush = true;
                    }
                } else {
                    throw new RuleSetException($this->getErrorMsg());
                }
            } else {                                                                               // If field is optional
                if (key_exists('value', $fieldSettings)) {                                         // If value is not empty
                    if (key_exists('optionalByRule', $fieldSettings) && $this->rule() !== true) {  // If field is optional by rule but rule isn't passed
                        continue;
                    }
                    $returnPush = true;
                }
            }

            if ($returnPush) {
                if (key_exists('instanceOf', $fieldSettings)) {                                    // If value is a submodel object call toArray function
                    if (!key_exists('instanceAsAttributes', $fieldSettings)) {
                        if (key_exists('multiple', $fieldSettings)) {
                            foreach ($fieldSettings['value'] as $fieldSettingSingle) {
                                $return[$xmlField][] = $this->changeDescriptionToValue($fieldSettingSingle);
                            }
                        } else {
                            $return[$xmlField] = $this->changeDescriptionToValue($fieldSettings['value']);
                        }
                    } else {                                                                       // If instanced submodel should be an attribute to current field
                        $return[$xmlField]['attributes'] = $fieldSettings['value']->toArray();
                    }
                } else {
                    if (key_exists('cdata', $fieldSettings)) {                                     // If value should be encapsulated inside CDATA tag
                        if (function_exists("mb_detect_encoding") && !mb_detect_encoding($fieldSettings['value'], 'UTF-8', true)) { // Check only if php mdstring extension is loaded
                            throw new ModelException("Value of '" . $fieldName . "' has to be encoded in UTF-8");
                        }
                    }

                    if (key_exists('isAttribute', $fieldSettings)) {
                        $return['attributes'][$xmlField]['value'] = $fieldSettings['value'];
                    } elseif (key_exists('isAttributeTo', $fieldSettings)) {                          // If current field should be an attribute to another field
                        $return[Util::changeCamelCaseToDash($fieldSettings['isAttributeTo'])]['attributes'][$xmlField]['value'] = $fieldSettings['value'];
                    } else {
                        if (key_exists('cdata', $fieldSettings)) {
                            $return[$xmlField]['cdata'] = $fieldSettings['value'];
                        } else {
                            $return[$xmlField]['value'] = $fieldSettings['value'];
                        }

                    }
                }

            }
        }

        return $return;
    }

    /**
     * Sets Description field as value of object.
     * Makes the setting of object (with attributes) and regular value (string) at the same field possible.
     * Field 'Description' will automatically set as value of object.
     *
     * @param $object
     * @return array
     */
    private function changeDescriptionToValue($object) {
        $tempArr = $object->toArray();
        if (key_exists('description', $tempArr)) {
            $tempArr = array_merge($tempArr['description'], $tempArr);
            unset($tempArr['description']);
        }
        return $tempArr;
    }

    /**
     * Returns error message
     *
     * @return string
     */
    public function getErrorMsg() {
        return $this->errorMsg;
    }

    /**
     * Sets error message
     *
     * @param string
     */
    public function setErrorMsg($errorMsg) {
        $this->errorMsg = $errorMsg;
    }

}
