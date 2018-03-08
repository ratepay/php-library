<?php

namespace RatePAY\Service;
use RatePAY\Exception\RequestException;
use RatePAY\ModelBuilder as ModelBuilder;

/**
 * Class OfflineInstallmentCalculation
 */
class OfflineInstallmentCalculation
{
    /**
     * Basket amount
     *
     * @var int
     */
    private $_basketAmount = 0;

    /**
     * Runtime
     *
     * @var int
     */
    private $_runtime = 0;

    /**
     * Service charge
     *
     * @var float
     */
    private $_serviceCharge = 0;

    /**
     * Interest rate
     *
     * @var float
     */
    private $_interestRate = 0;

    /**
     * Payment firstday
     *
     * @var int
     */
    private $_paymentFirstday = 0;

    /**
     * Set class attributes
     *
     * @param ModelBuilder $mbContent
     * @return $this
     */
    public function callOfflineCalculation(ModelBuilder $mbContent)
    {
        $calcContent = $mbContent->getModel()->getInstallmentCalculation();

        $this->_basketAmount = $calcContent->getAmount();
        $this->_runtime = $calcContent->getCalculationTime()->getMonth();
        $this->_serviceCharge = $calcContent->getServiceCharge();
        $this->_interestRate = $calcContent->getInterestRate();
        $this->_paymentFirstday = $calcContent->getPaymentFirstday();

        return $this;
    }

    /**
     * Call calculation method by subtype
     *
     * @param $subtype
     * @return float
     * @throws RequestException
     */
    public function subtype($subtype)
    {
        switch ($subtype) {
            case 'calculation-by-time' :
                return $this->callCalculationByTime();
                break;

            case '':
                throw new RequestException("Subtype is missing");
                break;

            default:
                throw new RequestException("Subtype '" . $subtype . "' not permitted");
                break;
        }
    }

    /**
     * Calculate installment by time
     *
     * @return float
     */
    private function callCalculationByTime()
    {
        $datePaymentFirstday = mktime(0, 0, 0, $this->_paymentFirstday == 28 ? (int) date("m") + 1 : (int) date("m") + 2, $this->_paymentFirstday, date("Y"));
        $today = time();
        $difference = $datePaymentFirstday - $today;

        $daysTillPaymentFirstday = ceil($difference / 60 / 60 / 24) + 1;

        $interestRateMonth =
            pow(
                (1 + ($this->_interestRate / 100)),
                (1 / 12)
            )
            -
            1;

        $interestRateTillStart =
            pow(
                (($this->_interestRate / 100) + 1),
                ($daysTillPaymentFirstday / 365)
            )
            -
            1;

        $installment =
            (
                $this->_basketAmount
                *
                (1 + ($interestRateTillStart))
                *
                $interestRateMonth
                *
                pow(
                    (1 + $interestRateMonth),
                    ($this->_runtime - 1)
                )
            )
            /
            (
                pow(
                    (1 + $interestRateMonth),
                    ($this->_runtime)
                )
                -
                1
            )
            +
            ($this->_serviceCharge / $this->_runtime);

        return round($installment, 2);
    }

}