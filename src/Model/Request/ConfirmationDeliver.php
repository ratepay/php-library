<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Request;

class ConfirmationDeliver extends AbstractRequest
{
    use TraitRequestContent;

    /**
     * Request rule set.
     *
     * @return bool
     */
    public function rule()
    {
        if (!key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg('Confirmation Deliver expects transaction id');

            return false;
        }

        return true;
    }
}
