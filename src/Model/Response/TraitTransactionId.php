<?php

namespace RatePAY\Model\Response;


trait TraitTransactionId
{

    /**
     * Transaction Id
     *
     * @var string
     */
    protected $transactionId = '';

    /**
     * Returns transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets transaction id
     *
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

}