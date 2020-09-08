<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2019 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Response;

    class PaymentQuery extends AbstractResponse
    {
        use TraitTransactionId;

        /**
         * Validates response.
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 402) {
                foreach ($this->getResponse()->content->products->product as $product) {
                    $this->setResult([(string) $product->attributes()->method]);
                }
                $this->setSuccessful();
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }

        /**
         * Returns allowed products / payment methods.
         *
         * @return array
         */
        public function getAdmittedPaymentMethods()
        {
            return $this->result;
        }
    }
