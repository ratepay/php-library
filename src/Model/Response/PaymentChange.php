<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Response;

    class PaymentChange extends AbstractResponse
    {
        use TraitTransactionId;

        /**
         * Validates response.
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 403) {
                $this->setSuccessful();
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }
    }
