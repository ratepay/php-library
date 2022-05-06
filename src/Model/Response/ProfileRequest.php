<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Response;

    class ProfileRequest extends AbstractResponse
    {
        /**
         * Validates response.
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 500) {
                $this->setResult(['merchantConfig' => (array) $this->getResponse()->content->{'master-data'}]);
                $this->setResult(['installmentConfig' => (array) $this->getResponse()->content->{'installment-configuration-result'}]);
                $this->setSuccessful();
            }
        }
    }
