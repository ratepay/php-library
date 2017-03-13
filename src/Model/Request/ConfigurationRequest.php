<?php

namespace RatePAY\Model\Request;

class ConfigurationRequest extends AbstractRequest
{

    /**
     * Request rule set
     *
     * @return bool
     */
    public function rule()
    {
        if (key_exists('value', $this->getHead()->admittedFields['TransactionId'])) {
            $this->setErrorMsg("Configuration Request does not allow transaction id");
            return false;
        }

        return true;
    }

}
