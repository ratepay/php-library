<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Response;

    class PaymentRequest extends AbstractResponse
    {
        use TraitTransactionId;

        /**
         * Retry admitted.
         *
         * @var string
         */
        protected $retryAdmitted = false;

        /**
         * Validates response.
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 402) {
                $this->setResult(['descriptor' => (string) $this->getResponse()->content->payment->descriptor]);
                $this->setResult(['address' => (array) $this->getResponse()->content->customer->addresses->address]);
                $this->setSuccessful();
            } elseif ($this->getResultCode() == 150 || $this->getResultCode() == 401) {
                if ($this->getResultCode() == 150) {
                    $this->setRetryAdmitted();
                }
                $this->setResult(['customerMessage' => (string) $this->getResponse()->head->processing->{'customer-message'}]);
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }

        /**
         * Returns customer message.
         *
         * @return string
         */
        public function getCustomerMessage()
        {
            return (key_exists('customerMessage', $this->result)) ? $this->result['customerMessage'] : '';
        }

        /**
         * Returns descriptor.
         *
         * @return string
         */
        public function getDescriptor()
        {
            return (key_exists('descriptor', $this->result)) ? $this->result['descriptor'] : '';
        }

        /**
         * Returns address.
         *
         * @return array
         */
        public function getAddress()
        {
            return (key_exists('address', $this->result)) ? $this->result['address'] : [];
        }

        /**
         * Is another attempt admitted.
         *
         * @return bool
         */
        public function isRetryAdmitted()
        {
            return $this->retryAdmitted;
        }

        /**
         * Sets retry admitted to positive.
         */
        protected function setRetryAdmitted()
        {
            $this->retryAdmitted = true;
        }
    }
