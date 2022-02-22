<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

use RatePAY\Exception\OfflineInstalmentCalculationException;
use RatePAY\Exception\RequestException;
use RatePAY\ModelBuilder as ModelBuilder;
use RatePAY\Service\DateTime\DateTime;

/**
 * Class OfflineInstallmentCalculation.
 */
class OfflineInstallmentCalculation
{
    /**
     * Basket amount.
     *
     * @var int
     */
    private $_basketAmount = 0;

    /**
     * Runtime.
     *
     * @var int
     */
    private $_runtime = 0;

    /**
     * Service charge.
     *
     * @var float
     */
    private $_serviceCharge = 0;

    /**
     * Interest rate.
     *
     * @var float
     */
    private $_interestRate = 0;

    /**
     * Payment firstday.
     *
     * @var int
     */
    private $_paymentFirstday = 0;

    /**
     * Set class attributes.
     *
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
     * Call calculation method by subtype.
     *
     * @param $subtype
     *
     * @return float
     *
     * @throws RequestException
     */
    public function subtype($subtype)
    {
        switch ($subtype) {
            case 'calculation-by-time':
                return (round($this->_interestRate, 2) > 0) ? $this->callCalculationByTime() : $this->callZeroPercentCalculationByTime();
                break;

            case '':
                throw new RequestException('Subtype is missing');
                break;

            default:
                throw new RequestException("Subtype '" . $subtype . "' not permitted");
                break;
        }
    }

    /**
     * Calculate installment by time.
     *
     * @return float
     */
    private function callCalculationByTime()
    {
        $totalMonths = (int) ($this->_runtime ?: 0);

        if ($totalMonths === 0) {
            throw new OfflineInstalmentCalculationException('Runtime of 0 months not allowed.');
        }

        $daysUntilFirstDueDate = DateTime::today()
            ->addMonths($this->_paymentFirstday == 28 ? 1 : 2)
            ->setDay($this->_paymentFirstday + 1)
            ->diffInDays(DateTime::today());
        $monthlyInterestRate = Math::interestByInterval($this->_interestRate, (1 / 12));
        $interestRateUntilFirstDue = Math::interestByInterval($this->_interestRate, ($daysUntilFirstDueDate / 365));
        $interestRateProduct = pow((1 + $monthlyInterestRate), $totalMonths) - 1;
        $monthlyServiceCharge = $this->_serviceCharge / $totalMonths;
        $paymentStreamFactor = $this->_basketAmount * (1 + $interestRateUntilFirstDue) * $monthlyInterestRate;

        $monthlyInstalment = ($paymentStreamFactor / $interestRateProduct)
            * pow((1 + $monthlyInterestRate), ($totalMonths - 1))
            + $monthlyServiceCharge;

        return round($monthlyInstalment, 2);
    }

    private function callZeroPercentCalculationByTime()
    {
        if ((int) $this->_runtime === 0) {
            throw new OfflineInstalmentCalculationException('Runtime of 0 months not allowed.');
        }

        $monthlyInstalment = $this->_basketAmount / $this->_runtime;
        $charges = $this->_serviceCharge / $this->_runtime;

        return round($monthlyInstalment + $charges, 2);
    }
}
