<?php

    namespace RatePAY\Model\Response;

    class PaymentConfirm extends AbstractResponse
    {

        use TraitTransactionId;

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 400) {
                $this->setSuccessful();
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }

    }
