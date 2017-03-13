<?php

    namespace RatePAY\Model\Response;

    use RatePAY\Service\LanguageService as lang;

    class CalculationRequest extends AbstractResponse
    {

        /**
         * Success codes
         *
         * @var array
         */
        private $successCodes = [603, 671, 688, 689, 695, 696, 697, 698, 699];

        /**
         * Validates response
         */
        public function validateResponse()
        {
            if ($this->getStatusCode() == "OK" && $this->getResultCode() == 502 && in_array($this->getReasonCode(), $this->getSuccessCodes())) {
                $this->setResult(['totalAmount' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'total-amount'}]);
                $this->setResult(['amount' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'amount'}]);
                $this->setResult(['interestRate' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'interest-rate'}]);
                $this->setResult(['interestAmount' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'interest-amount'}]);
                $this->setResult(['serviceCharge' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'service-charge'}]);
                $this->setResult(['annualPercentageRate' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'annual-percentage-rate'}]);
                $this->setResult(['monthlyDebitInterest' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'monthly-debit-interest'}]);
                $this->setResult(['numberOfRatesFull' => (int) $this->getResponse()->content->{'installment-calculation-result'}->{'number-of-rates'}]);
                $this->setResult(['numberOfRates' => (int) $this->getResponse()->content->{'installment-calculation-result'}->{'number-of-rates'} - 1]);
                $this->setResult(['rate' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'rate'}]);
                $this->setResult(['lastRate' => (float) $this->getResponse()->content->{'installment-calculation-result'}->{'last-rate'}]);
                $this->setResult(['paymentFirstday' => (int) $this->getResponse()->content->{'installment-calculation-result'}->{'payment-firstday'}]);
                $this->setSuccessful();
            }
        }

        ### Following methods are used for Payment Request -> Payment section

        /**
         * Returns all success codes
         *
         * @return array
         */
        private function getSuccessCodes()
        {
            return $this->successCodes;
        }

        /**
         * Returns amount value for payment section (Payment Request)
         *
         * @return float
         */
        public function getPaymentAmount()
        {
            return $this->result['totalAmount'];
        }

        /**
         * Returns number of rates for installment details (Payment Request -> Payment)
         *
         * @return int
         */
        public function getInstallmentNumber()
        {
            return $this->result['numberOfRatesFull'];
        }

        /**
         * Returns rate for installment details (Payment Request -> Payment)
         *
         * @return float
         */
        public function getInstallmentAmount()
        {
            return $this->result['rate'];
        }

        /**
         * Returns last rate for installment details (Payment Request -> Payment)
         *
         * @return float
         */
        public function getLastInstallmentAmount()
        {
            return $this->result['lastRate'];
        }

        /**
         * Returns interest rate for installment details (Payment Request -> Payment)
         *
         * @return float
         */
        public function getInterestRate()
        {
            return $this->result['interestRate'];
        }

        /**
         * Returns payment firstday for installment details (Payment Request -> Payment)
         *
         * @return int
         */
        public function getPaymentFirstday()
        {
            return $this->result['paymentFirstday'];
        }
        
        ### Following methods are used for installment calculation

        /**
         * Returns service charge
         *
         * @return float
         */
        public function getServiceCharge()
        {
            return number_format($this->result['serviceCharge'], 2, ",", ".");
        }

        /**
         * Returns annual percentage rate
         *
         * @return float
         */
        public function getAnnualPercentageRate()
        {
            return number_format($this->result['annualPercentageRate'], 2, ",", ".");
        }

        /**
         * Returns monthly debit interest
         *
         * @return float
         */
        public function getMonthlyDebitInterest()
        {
            return number_format($this->result['monthlyDebitInterest'], 2, ",", ".");
        }

        /**
         * Returns interest amount
         *
         * @return float
         */
        public function getInterestAmount()
        {
            return number_format($this->result['interestAmount'], 2, ",", ".");
        }

        /**
         * Returns total amount
         *
         * @return float
         */
        public function getTotalAmount()
        {
            return number_format($this->result['totalAmount'], 2, ",", ".");
        }

        /**
         * Returns number of full rates
         *
         * @return int
         */
        public function getNumberOfRatesFull()
        {
            return $this->result['numberOfRatesFull'];
        }

        /**
         * Returns total number of rates
         *
         * @return int
         */
        public function getNumberOfRates()
        {
            return $this->result['numberOfRates'];
        }

        /**
         * Returns rate
         *
         * @return float
         */
        public function getRate()
        {
            return number_format($this->result['rate'], 2, ",", ".");
        }

        /**
         * Returns last rate
         *
         * @return float
         */
        public function getLastRate()
        {
            return number_format($this->result['lastRate'], 2, ",", ".");
        }

    }
