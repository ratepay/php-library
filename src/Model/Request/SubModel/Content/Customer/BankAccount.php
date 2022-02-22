<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request\SubModel\Content\Customer;

use RatePAY\Model\Request\SubModel\AbstractModel;

/**
 * @method $this  setOwner(string $owner)
 * @method string getOwner()
 * @method $this  setBankName(string $bankName)
 * @method string getBankName()
 * @method $this  setBankAccountNumber(string $bankAccountNumber)
 * @method string getBankAccountNumber()
 * @method $this  setBankCode(string $bankCode)
 * @method string getBankCode()
 * @method $this  setIban(string $iban)
 * @method string getIban()
 * @method $this  setBicSwift(string $bicSwift)
 * @method string getBicSwift()
 * @method $this  setReference(string $reference)
 * @method string getReference()
 */
class BankAccount extends AbstractModel
{
    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set bank code value by using setBankCode(var). Get bank code by using getBankCode(). (Please consider the camel case).
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
        'Owner' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
        'BankName' => [
            'mandatory' => false,
            'cdata' => true,
        ],
        'BankAccountNumber' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
        'BankCode' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
        'Iban' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
        'BicSwift' => [
            'mandatory' => false, // BicSwift is only for customers with billing address in germany optional
            'cdata' => true,
        ],
        'Reference' => [
            'mandatoryByRule' => true,
            'cdata' => true,
        ],
    ];

    /**
     * Bank data rule : if classic bank account number is used bank code is mandatory.
     *
     * @return bool
     */
    protected function rule()
    {
        if (isset($this->admittedFields['Reference']['value'])) {
            return true;
        }

        if (!isset($this->admittedFields['Owner']['value'])) {
            $this->setErrorMsg('Bank account owner is required');

            return false;
        }

        if (isset($this->admittedFields['BankAccountNumber']['value'])) {
            if (!isset($this->admittedFields['BankCode']['value'])) {
                $this->setErrorMsg('Bank code is required');

                return false;
            } else {
                return true;
            }
        }

        if (isset($this->admittedFields['Iban']['value'])) {
            return true;
        }

        $this->setErrorMsg('Bank account number or IBAN are required');

        return false;
    }
}
