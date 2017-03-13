<?php

namespace RatePAY\Model\Request;

class CalculationRequest extends AbstractRequest
{

    use TraitRequestContent;
    use TraitRequestSubtype;

    /**
     * CalculationRequest constructor.
     * Defines subtype as required and sets admitted subtypes
     */
    public function __construct()
    {
        $this->setSubtypeAsRequired();
        $this->setAdmittedSubtypes(['calculation-by-rate', 'calculation-by-time']);
    }

    // ToDo : Reducing subtype to 'rate' and 'time'

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Calculation Request does not allow transaction id");
            return false;
        }

        return true;
    }

}
