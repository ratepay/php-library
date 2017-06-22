<?php

namespace RatePAY\Model\Response;


abstract class AbstractResponse
{

    /**
     * Request response
     *
     * @var \SimpleXmlElement
     */
    protected $response;

    /**
     * Successful
     *
     * @var bool
     */
    protected $successful = false;

    /**
     * Status code
     *
     * @var string
     */
    protected $statusCode = '';

    /**
     * Status message
     *
     * @var string
     */
    protected $statusMessage = '';

    /**
     * Result code
     *
     * @var int
     */
    protected $resultCode = null;

    /**
     * Result message
     *
     * @var string
     */
    protected $resultMessage = '';

    /**
     * Reason code
     *
     * @var int
     */
    protected $reasonCode = null;

    /**
     * Reason message
     *
     * @var string
     */
    protected $reasonMessage = '';

    /**
     * Result model
     *
     * @var array
     */
    protected $result = [];

    /**
     * AbstractResponse constructor. Sets response.
     * @param \SimpleXmlElement $response
     */
    public function __construct(\SimpleXmlElement $response = null)
    {
        if ($response !== null) {
            $this->setResponse($response);

            $this->setStatusCode((string) $this->getResponse()->head->processing->status->attributes()->code);
            $this->setStatusMessage((string) $this->getResponse()->head->processing->status);
            $this->setResultCode((int) $this->getResponse()->head->processing->result->attributes()->code);
            $this->setResultMessage((string) $this->getResponse()->head->processing->result);
            $this->setReasonCode((int) $this->getResponse()->head->processing->reason->attributes()->code);
            $this->setReasonMessage((string) $this->getResponse()->head->processing->reason);
        }
    }

    /**
     * Child classes has to provide a validateResponse method
     */
    public abstract function validateResponse();


    /**
     * Get response object
     *
     * @return \SimpleXmlElement
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Set response object
     *
     * @param \SimpleXmlElement $response
     */
    public function setResponse(\SimpleXmlElement $response) {
        $this->response = $response;
    }

    /**
     * Is response successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Sets successful flag
     */
    protected function setSuccessful()
    {
        $this->successful = true;
    }

    /**
     * Returns result array
     *
     * @return array
     */
    public function getResult($json = false)
    {
        return ($json) ? json_encode($this->result) : $this->result;
    }

    /**
     * Sets result array
     *
     * @param array $result
     */
    public function setResult(array $result)
    {
        $this->result = (count($this->result) > 0) ? array_merge($this->result, $result) : $result;
    }

    /**
     * Returns status code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets status code
     *
     * @param string $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Returns status message
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * Sets status message
     *
     * @param string $statusMessage
     */
    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;
    }

    /**
     * Returns reason code
     *
     * @return int
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Sets reason code
     *
     * @param integer $reasonCode
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
    }

    /**
     * Returns reason message
     *
     * @return int
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * Sets reason message
     *
     * @param string $reasonMessage
     */
    public function setReasonMessage($reasonMessage)
    {
        $this->reasonMessage = $reasonMessage;
    }

    /**
     * Returns result code
     *
     * @return int
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * Sets result code
     *
     * @param integer $resultCode
     */
    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
    }

    /**
     * Returns result message
     *
     * @return int
     */
    public function getResultMessage()
    {
        return $this->resultMessage;
    }

    /**
     * Sets result message
     *
     * @param string $resultMessage
     */
    public function setResultMessage($resultMessage)
    {
        $this->resultMessage = $resultMessage;
    }

}