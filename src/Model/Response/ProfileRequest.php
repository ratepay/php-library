<?php

/*
 * RatePAY PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2020 RatePAY GmbH / Berlin / Germany
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
