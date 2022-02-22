<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request;

class PaymentQuery extends AbstractRequest
{
    use TraitRequestContent;
    use TraitRequestSubtype;

    /**
     * PaymentQuery constructor.
     * Defines subtype as required and sets admitted subtypes.
     */
    public function __construct()
    {
        $this->setSubtypeAsRequired();
        $this->setAdmittedSubtypes(['full']);

        // ToDo: Create rule which sets 'full' automatically
    }

    /**
     * Request rule set.
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg('Payment Query expects transaction id');

            return false;
        }

        return true;
    }
}
