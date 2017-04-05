<?php

    namespace RatePAY\Model\Response;

    class ConfigurationRequest extends AbstractResponse
    {

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 500) {
                $this->setResult(['interestrateMin' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'interestrate-min'}]);
                $this->setResult(['interestRateDefault' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'interestrate-default'}]);
                $this->setResult(['interestRateMax' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'interestrate-max'}]);
                $this->setResult(['interestRateMerchantTowardsBank' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'interest-rate-merchant-towards-bank'}]);
                $this->setResult(['monthNumberMin' => (int) $this->getResponse()->content->{'installment-configuration-result'}->{'month-number-min'}]);
                $this->setResult(['monthNumberMax' => (int) $this->getResponse()->content->{'installment-configuration-result'}->{'month-number-max'}]);
                $this->setResult(['monthLongrun' => (int) $this->getResponse()->content->{'installment-configuration-result'}->{'month-longrun'}]);
                $this->setResult(['amountMinLongrun' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'amount-min-longrun'}]);
                $this->setResult(['monthAllowed' => array_map('intval', explode(',', (string) $this->getResponse()->content->{'installment-configuration-result'}->{'month-allowed'}))]);
                $this->setResult(['validPaymentFirstdays' => $this->getResponse()->content->{'installment-configuration-result'}->{'valid-payment-firstdays'}]);
                $validPaymentFirstdays = (string) $this->getResponse()->content->{'installment-configuration-result'}->{'valid-payment-firstdays'};
                $this->setResult(['validPaymentFirstdays' => (!strstr($validPaymentFirstdays, ',')) ? (int) $validPaymentFirstdays : array_map('intval', explode(',', $validPaymentFirstdays))]);
                $this->setResult(['paymentFirstday' => (int) $this->getResponse()->content->{'installment-configuration-result'}->{'payment-firstday'}]);
                $this->setResult(['paymentAmount' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'payment-amount'}]);
                $this->setResult(['paymentLastrate' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'payment-lastrate'}]);
                $this->setResult(['rateMinNormal' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'rate-min-normal'}]);
                $this->setResult(['rateMinLongrun' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'rate-min-longrun'}]);
                $this->setResult(['serviceCharge' => (float) $this->getResponse()->content->{'installment-configuration-result'}->{'service-charge'}]);
                $this->setResult(['minDifferenceDueday' => (int) $this->getResponse()->content->{'installment-configuration-result'}->{'min-difference-dueday'}]);
                $this->setSuccessful();
            }
        }

        /**
         * Returns allowed months. If order amount is entered it returns only admitted month for specific order amount.
         *
         * @param float $orderAmount
         * @return array
         */
        public function getAllowedMonths($orderAmount = 0)
        {
            if ($orderAmount == 0) {
                return $this->result['monthAllowed'];
            }

            $allowedMonths = $this->result['monthAllowed'];
            $possibleMonths = [];

            $rateMinNormal = $this->result['rateMinNormal'];
            $interestRate = $this->result['interestRateDefault'] / 100;
            $interestRateMonth = $interestRate / 12;

            foreach ($allowedMonths as $runtime){
                $rateAmount = ceil($orderAmount * (($interestRateMonth * pow((1 + $interestRateMonth), $runtime)) / (pow((1 + $interestRateMonth), $runtime) - 1)));

                if($rateAmount >= $rateMinNormal){
                    $possibleMonths[] = $runtime;
                }
            }

            return $possibleMonths;
        }

        /**
         * Returns minimum rate
         *
         * @param float $orderAmount
         * @return array
         */
        public function getMinRate($orderAmount = 0)
        {
            //return ($orderAmount >= $this->result['amountMinLongrun']) ? $this->result['rateMinLongrun'] : $this->result['rateMinNormal'];
            return $this->result['rateMinNormal'];
        }

        /**
         * Returns maximum rate
         *
         * @param float $orderAmount
         * @return int
         */
        public function getMaxRate($orderAmount)
        {
            $maxRate = $orderAmount / min($this->result['monthAllowed']);

            return ceil($maxRate);
        }

        /**
         * Returns valid payment firstdays
         *
         * @return int|array
         */
        public function getValidPaymentFirstdays()
        {
            return $this->result['validPaymentFirstdays'];
        }


    }
