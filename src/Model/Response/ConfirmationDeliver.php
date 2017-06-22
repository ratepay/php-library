<?php

    namespace RatePAY\Model\Response;

    class ConfirmationDeliver extends AbstractResponse
    {

        use TraitTransactionId;

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 404) {
                $this->setSuccessful();
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }

    }
