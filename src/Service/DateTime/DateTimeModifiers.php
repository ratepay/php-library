<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service\DateTime;

trait DateTimeModifiers
{
    /**
     * @param int $day days of month to set
     *
     * @return DateTime
     */
    public function setDay($day)
    {
        $date = $this->getDateFields();

        return $this->setDate($date->year, $date->month, $day);
    }

    /**
     * @param int $daysOffset number of days to be added
     *
     * @return DateTime
     */
    public function addDays($daysOffset)
    {
        $date = $this->getDateFields();

        return $this->setDate($date->year, $date->month, $date->day + $daysOffset);
    }

    /**
     * @param int $month month of year to set
     *
     * @return DateTime
     */
    public function setMonth($month)
    {
        $date = $this->getDateFields();

        return $this->setDate($date->year, $month, $date->day);
    }

    /**
     * @param int $monthsOffset number of months to be added
     *
     * @return DateTime
     */
    public function addMonths($monthsOffset)
    {
        $date = $this->getDateFields();

        return $this->setDate($date->year, $date->month + $monthsOffset, $date->day);
    }

    /**
     * @param int $year year to set
     *
     * @return DateTime
     */
    public function setYear($year)
    {
        $date = $this->getDateFields();

        return $this->setDate($year, $date->month, $date->day);
    }

    /**
     * @param int $yearsOffset number of years to be added
     *
     * @return DateTime
     */
    public function addYears($yearsOffset)
    {
        $date = $this->getDateFields();

        return $this->setDate($date->year + $yearsOffset, $date->month, $date->day);
    }

    /**
     * @return object
     */
    private function getDateFields()
    {
        return (object) [
            'year' => (int) $this->format('Y'),
            'month' => (int) $this->format('m'),
            'day' => (int) $this->format('d'),
        ];
    }
}
