<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Response;

    use RatePAY\ModelBuilder;
    use RatePAY\Service\OfflineInstallmentCalculation;

    class ConfigurationRequest extends AbstractResponse
    {
        /**
         * Validates response.
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 500) {
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
         * @param float $paymentFirstDay
         *
         * @return array
         */
        public function getAllowedMonths($orderAmount = 0, $paymentFirstDay = null)
        {
            if ($orderAmount == 0) {
                return $this->result['monthAllowed'];
            }

            $possibleMonths = [];

            foreach ($this->result['monthAllowed'] as $runtime) {
                $mbContent = new ModelBuilder('Content');
                $mbContent->setArray([
                    'InstallmentCalculation' => [
                        'Amount' => $orderAmount,
                        'PaymentFirstday' => $paymentFirstDay ? $paymentFirstDay : $this->result['paymentFirstday'],
                        'InterestRate' => $this->result['interestRateDefault'],
                        'ServiceCharge' => $this->result['serviceCharge'],
                        'CalculationTime' => [
                            'Month' => $runtime,
                        ],
                    ],
                ]);

                $monthlyRate = (new OfflineInstallmentCalculation())->callOfflineCalculation($mbContent)->subtype('calculation-by-time');
                if ($monthlyRate >= $this->result['rateMinNormal']) {
                    $possibleMonths[] = $runtime;
                }
            }

            return $possibleMonths;
        }

        /**
         * Returns minimum rate.
         *
         * @param float $orderAmount
         *
         * @return array
         */
        public function getMinRate($orderAmount = 0)
        {
            //return ($orderAmount >= $this->result['amountMinLongrun']) ? $this->result['rateMinLongrun'] : $this->result['rateMinNormal'];
            return $this->result['rateMinNormal'];
        }

        /**
         * Returns maximum rate.
         *
         * @param float $orderAmount
         *
         * @return int
         */
        public function getMaxRate($orderAmount)
        {
            $maxRate = $orderAmount / min($this->result['monthAllowed']);

            return ceil($maxRate);
        }

        /**
         * Returns valid payment firstdays.
         *
         * @return int|array
         */
        public function getValidPaymentFirstdays()
        {
            return $this->result['validPaymentFirstdays'];
        }
    }
