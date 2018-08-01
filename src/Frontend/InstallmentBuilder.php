<?php

namespace RatePAY\Frontend;

use RatePAY\Model\Request\ConfigurationRequest;
use RatePAY\ModelBuilder;
use RatePAY\RequestBuilder;
use RatePAY\Service\Util;
use RatePAY\Service\LanguageService;
use RatePAY\Exception\RequestException;

class InstallmentBuilder
{

    /**
     * Sandbox mode
     *
     * @var bool
     */
    private $sandbox = false;

    /**
     * RatePAY profile id
     *
     * @var string
     */
    private $profileId;

    /**
     * RatePAY security code
     *
     * @var string
     */
    private $securitycode;

    /**
     * Language object contains translation text blocks
     *
     * @var LanguageService
     */
    private $lang;

    /**
     * Customers billing country
     *
     * @var string
     */
    private $billingCountry = "DE";

    /**
     * Connect timeout
     *
     * @var int
     */
    private $connectionTimeout = 0;

    /**
     * Execution timeout
     *
     * @var int
     */
    private $executionTimeout = 0;

    /**
     * Connection retries
     *
     * @var int
     */
    private $connectionRetries = 0;

    /**
     * Retry delay
     *
     * @var int
     */
    private $retryDelay = 0;

    /**
     * DebitPayTypes
     * @ToDo: find better place to save (but stay compatible with PHP 5.4 (now array within constant))
     */
    private $debitPayTypes = [
        2 => "DIRECT-DEBIT",
        28 => "BANK-TRANSFER"
    ];

    public function __construct($sandbox = false, $profileId = null, $securitycode = null, $language = "DE", $country = "DE")
    {
        if ($sandbox) {
            $this->sandbox = true;
        }

        if (!is_null($profileId)) {
            $this->setProfileId($profileId);
        }

        if (!is_null($securitycode)) {
            $this->setSecuritycode($securitycode);
        }

        $this->lang = new LanguageService($language);
        $this->billingCountry = $country;
    }

    /**
     * Sets RatePAY profile id
     *
     * @param string $profileId
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    /**
     * Sets RatePAY security code
     * @param string $securitycode
     */
    public function setSecuritycode($securitycode)
    {
        $this->securitycode = $securitycode;
    }

    /**
     * Sets current language
     *
     * @param $language
     * @throws \RatePAY\Exception\LanguageException
     */
    public function setLanguage($language)
    {
        $this->lang = new LanguageService($language);
    }

    /**
     * Sets customer billing country which is necessary to allow classic account number
     *
     * @param $country
     */
    public function setBillingCountry($country)
    {
        $this->billingCountry = $country;
    }

    /**
     * Calls Configuration Request
     *
     * @return ConfigurationRequest
     * @throws RequestException
     */
    private function getInstallmentConfiguration()
    {
        $rb = new RequestBuilder($this->sandbox);
        $rb->setConnectionTimeout($this->connectionTimeout)
            ->setExecutionTimeout($this->executionTimeout)
            ->setConnectionRetries($this->connectionRetries)
            ->setRetryDelay($this->retryDelay);
        $configuration = $rb->callConfigurationRequest($this->getHead());

        if (!$configuration->isSuccessful()) {
            throw new RequestException("Configuration Request not successful - reason: '" . $configuration->getReasonMessage() . "'");
        }

        return $configuration;
    }

    /**
     * Returns processed html template
     *
     * @param float $amount
     * @param string $template
     * @return string
     * @throws RequestException
     */
    public function getInstallmentCalculatorByTemplate($amount, $template)
    {
        $configuration = $this->getInstallmentConfiguration();

        $replacements = array_merge(
            ['rp_minimumRate' => $configuration->getMinRate()],
            ['rp_maximumRate' => $configuration->getMaxRate($amount)],
            ['rp_amount'      => $amount],
            $this->getDebitPayType($configuration->getValidPaymentFirstdays()),
            $this->lang->getArray()
        );

        $returnTemplate = Util::templateReplace($template, $replacements);
        $returnTemplate = Util::templateLoop($returnTemplate, ['rp_allowedMonths' => $configuration->getAllowedMonths($amount)]);

        return $returnTemplate;
    }

    /**
     * Returns installment configuration as JSON
     *
     * @param $amount
     * @return string
     * @throws RequestException
     */
    public function getInstallmentCalculatorAsJson($amount)
    {
        $configuration = $this->getInstallmentConfiguration();

        return json_encode([
            'rp_minimumRate'   => $configuration->getMinRate(),
            'rp_maximumRate'   => $configuration->getMaxRate($amount),
            'rp_allowedMonths' => $configuration->getAllowedMonths($amount),
            'rp_debitPayType' => $this->getDebitPayType($configuration->getValidPaymentFirstdays())
        ]);
    }


    /**
     * Calls CalculationRequest
     *
     * @param $type
     * @param $value
     * @param $amount
     * @param null $firstday
     * @return CalculationRequest
     * @throws RequestException
     * @throws \RatePAY\Exception\ModelException
     */
    private function getInstallmentCalculation($type, $value, $amount, $firstday = null)
    {
        if (floatval($value) <= 0) {
            throw new RequestException("Invalid calculation value");
        }

        if (floatval($amount) <= 0) {
            throw new RequestException("Invalid calculation amount");
        }

        $installmentCalculation = [
            'InstallmentCalculation' => [
                'Amount' => $amount
            ]
        ];

        switch ($type) {
            case 'time':
                $installmentCalculation['InstallmentCalculation']['CalculationTime']['Month'] = $value;
                break;
            case 'rate':
                $installmentCalculation['InstallmentCalculation']['CalculationRate']['Rate'] = $value;
                break;
            default:
                throw new RequestException("Invalid calculation type. 'time' or 'rate' expected");
        }

        if (!is_null($firstday)) {
            $installmentCalculation['InstallmentCalculation']['PaymentFirstday'] = $firstday;
        }

        $mbContent = new ModelBuilder('Content');
        $mbContent->setArray($installmentCalculation);

        $rb = new RequestBuilder($this->sandbox);
        $rb->setConnectionTimeout($this->connectionTimeout)
            ->setExecutionTimeout($this->executionTimeout)
            ->setConnectionRetries($this->connectionRetries)
            ->setRetryDelay($this->retryDelay);
        $calculation = $rb->callCalculationRequest($this->getHead(), $mbContent)->subtype('calculation-by-' . $type);
        // ToDo: Surround with Try-Catch-Block

        if (!$calculation->isSuccessful()) {
            throw new RequestException("Calculation Request not successful - reason: '" . $calculation->getReasonMessage() . "'");
        }

        return $calculation;
    }

    /**
     * Returns processed html template
     *
     * @param $amount
     * @param $type
     * @param $value
     * @param $template
     * @return string
     */
    public function getInstallmentPlanByTemplate($template, $amount, $type, $value, $firstday = null)
    {
        $calculation = $this->getInstallmentCalculation($type, $value, $amount, $firstday);

        $rpReasonCodeTranslation = 'rp_reason_code_translation_' . $calculation->getReasonCode();

        $replacements = array_merge(
            [
                'rp_amount'                      => number_format($amount, 2, ",", "."),
                'rp_serviceCharge'               => $calculation->getServiceCharge(),
                'rp_annualPercentageRate'        => $calculation->getAnnualPercentageRate(),
                'rp_monthlyDebitInterest'        => $calculation->getMonthlyDebitInterest(),
                'rp_interestRate'                => $calculation->getInterestRate(),
                'rp_interestAmount'              => $calculation->getInterestAmount(),
                'rp_totalAmount'                 => $calculation->getTotalAmount(),
                'rp_numberOfRatesFull'           => $calculation->getNumberOfRatesFull(),
                'rp_numberOfRatesDecreasedByOne' => $calculation->getNumberOfRatesFull() - 1,
                'rp_numberOfRates'               => $calculation->getNumberOfRates(),
                'rp_rate'                        => $calculation->getRate(),
                'rp_lastRate'                    => $calculation->getLastRate(),
                'rp_responseText'                => $this->lang->$rpReasonCodeTranslation()
            ],
            $this->lang->getArray()
        );

        return Util::templateReplace($template, $replacements);
    }

    /**
     * Returns installment calculation as JSON
     *
     * @param $amount
     * @param $type
     * @param $value
     * @return string
     */
    public function getInstallmentPlanAsJson($amount, $type, $value)
    {
        $configuration = $this->getInstallmentCalculation($type, $value, $amount);

        return json_encode($configuration->getResult());
    }

    /**
     * Returns common head model
     *
     * @return \RatePAY\ModelBuilder
     */
    private function getHead()
    {
        $mbHead = new ModelBuilder();
        $mbHead->setArray([
            'SystemId' => "RatePAY API PHP SDK",
            'Credential' => [
                'ProfileId' => $this->profileId,
                'Securitycode' => $this->securitycode
            ]
        ]);

        return $mbHead;
    }

    /**
     * Maps valid first days to pay types
     *
     * @param array|int $validPaymentFirstdays
     * @return array
     */
    private function getDebitPayType($validPaymentFirstdays)
    {
        $result = [
            'rp_paymentType_directDebit' => false,
            'rp_paymentType_bankTransfer' => false
        ];

        if (is_array($validPaymentFirstdays)) {
            foreach ($validPaymentFirstdays as $validPaymentFirstday) {
                $result = Util::merge_array_replace($result, $this->getDebitPayType($validPaymentFirstday));
            }
        } else {
            if (key_exists($validPaymentFirstdays, $this->debitPayTypes)) {
                switch ($this->debitPayTypes[$validPaymentFirstdays]) {
                    case "DIRECT-DEBIT":
                        $result['rp_paymentType_directDebit'] = true;
                        $result['rp_paymentType_directDebit_firstday'] = $validPaymentFirstdays;
                        break;
                    case "BANK-TRANSFER":
                        $result['rp_paymentType_bankTransfer'] = true;
                        $result['rp_paymentType_bankTransfer_firstday'] = $validPaymentFirstdays;
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * Sets the number of milliseconds to wait while trying to connect
     *
     * @param int $timeout
     * @return $this
     */
    public function setConnectionTimeout($timeout = 0)
    {
        $this->connectionTimeout = (int) $timeout;
        return $this;
    }

    /**
     * Sets the maximum number of milliseconds to allow cURL functions to execute
     *
     * @param int $timeout
     * @return $this
     */
    public function setExecutionTimeout($timeout = 0)
    {
        $this->executionTimeout = (int) $timeout;
        return $this;
    }

    /**
     * Sets the number of retries
     *
     * @param int $retries
     * @return $this
     */
    public function setConnectionRetries($retries = 0)
    {
        $this->connectionRetries = (int) $retries;
        return $this;
    }

    /**
     * Sets the delay between retries in milliseconds
     *
     * @param int $delay
     * @return $this
     */
    public function setRetryDelay($delay = 0)
    {
        $this->retryDelay = (int) $delay;
        return $this;
    }

}
