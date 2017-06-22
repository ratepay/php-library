<?php

    namespace RatePAY\Model\Response;

    class PaymentQuery extends AbstractResponse
    {

        use TraitTransactionId;

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 402) {
                foreach ($this->getResponse()->content->products->product as $product) {
                    $this->setResult([(string) $product->attributes()->method]);
                }
                $this->setSuccessful();
            }

            $this->setTransactionId((string) $this->getResponse()->head->{'transaction-id'});
        }

        /**
         * Returns allowed products / payment methods
         *
         * @return array
         */
        public function getAdmittedPaymentMethods()
        {
            return $this->result;
        }

    }
