<?php

    namespace RatePAY\Model\Response;

    class ProfileRequest extends AbstractResponse
    {

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 500) {
                $this->setResult(['merchantConfig' => (array) $this->getResponse()->content->{'master-data'}]);
                $this->setResult(['installmentConfig' => (array) $this->getResponse()->content->{'installment-configuration-result'}]);
                $this->setSuccessful();
            }
        }

    }
