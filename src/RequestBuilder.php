<?php

namespace RatePAY;

use RatePAY\Service\Util;
use RatePAY\Service\XmlBuilder;
use RatePAY\Service\CommunicationService;
use RatePAY\Service\ValidateGatewayResponse;
use RatePAY\Service\ModelMapper;
use RatePAY\Exception\RequestException;

/**
 * Class RequestBuilder
 * @package Request
 */
class RequestBuilder
{

    /**
     * Instance of current request class
     *
     * @var mixed
     */
    private $request;

    /**
     * Instance of ValidateGatewayResponse
     *
     * @var ValidateGatewayResponse
     */
    private $response;

    /**
     * Instance of ValidateGatewayResponse
     *
     * @var ValidateGatewayResponse
     */
    private $responseModel;

    /**
     * Response time
     *
     * @var float
     */
    private $responseTime;

    /**
     * Raw XML request string
     *
     * @var string
     */
    private $requestRaw;

    /**
     * Raw XML response string
     *
     * @var string
     */
    private $responseRaw;

    /**
     * Request object
     *
     * @var SimpleXMLElement
     */
    private $requestXmlElement;

    /**
     * Response object
     *
     * @var SimpleXMLElement
     */
    private $responseXmlElement;

    /**
     * Sandbox mode
     *
     * @var bool
     */
    private $sandbox = false;

    /**
     * Head object
     *
     * @var Head
     */
    private $head = null;

    /**
     * Content object
     *
     * @var Content
     */
    private $content = null;

    /**
     * Request type (Operation)
     *
     * @var string
     */
    private $requestType = null;

    /**
     * Operation subtype
     *
     * @var string
     */
    private $subtype = null;

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
     * RequestBuilder constructor. Sets sandbox mode
     *
     * @param bool $sandbox
     */
    public function __construct($sandbox = false)
    {
        if ($sandbox) {
            $this->sandbox = true;
        }
    }

    /**
     * Magic method to catch and proceed request calls.
     * The call by magic method was chosen to secure the modularity of the requests.
     *
     * Returns the current instance.
     *
     * @param $name
     * @param $arguments
     * @return RequestBuilder
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 4) == "call") {
            $this->clearAttributes(); // Clearing all former attributes in case of reuse of current instance of RequestBuilder

            $requestModelName = substr($name, 4);
            $requestModelWithPath = ModelMapper::getFullPathRequestModel($requestModelName);

            if (!class_exists($requestModelWithPath)) {
                throw new RequestException("operation '" . $requestModelName . "' not valid");
            }

            $this->requestModel = new $requestModelWithPath;

            if (get_class($arguments[0]) == "RatePAY\\ModelBuilder") {
                $arguments[0] = $arguments[0]->getModel();
            }

            if (!key_exists(0, $arguments) || get_class($arguments[0]) != ModelMapper::getFullPathRequestSubModel("Head")) {
                throw new RequestException("" . $requestModelName . " requires Head model");
            }

            $this->requestType = $requestModelName;
            $this->head = $arguments[0];

            if (key_exists(1, $arguments)) {
                if (get_class($arguments[1]) == "RatePAY\\ModelBuilder") {
                    $arguments[1] = $arguments[1]->getModel();
                }

                if (get_class($arguments[1]) != ModelMapper::getFullPathRequestSubModel("Content")) {
                    throw new RequestException("" . $requestModelName . " requires Content model");
                }

                $this->content = $arguments[1];
            }

            // If no subtype needed or already set, call request immediately. Else subtype method has to trigger the call.
            if (!$this->requestModel->isSubtypeRequired() || $this->head->isSubtypeSet()) {
                $this->callRequest();
            }

            return $this;

        } elseif (substr($name, 0, 3) == "get" || substr($name, 0, 2) == "is") {

            if (method_exists($this->responseModel, $name)) {
                if (count($arguments) > 0) {
                    return $this->responseModel->$name(current($arguments));
                } else {
                    return $this->responseModel->$name();
                }

            } else {
                throw new RequestException("Action '" . $name . "' not valid");
            }

        } else {
            throw new RequestException("Action '" . $name . "' not valid");
        }
    }

    /**
     * Calls requested request and saves results in class attributes
     */
    private function callRequest()
    {
        /*
         * If Payment Request is called without transaction id,
         * Payment Init will called automatically before sending Payment Request.
         */
        /*if ('PaymentRequest' == $requestModelName && !$head->isTransactionSet()) {
            $rB = new RequestBuilder($this->sandbox);
            $paymentInit = $rB->callPaymentInit($arguments[0]);
            if (!$paymentInit->isSuccessful()) {
                return $paymentInit;
            }
            $head->setTransactionId($paymentInit->getTransactionId());
        }*/

        $this->head->setOperation(Util::changeCamelCaseToUnderscore($this->requestType));
        if (!is_null($this->subtype)) {
            $this->head->setSubtype($this->subtype);
        }
        $this->requestModel->setHead($this->head);

        if (!is_null($this->content)) {
            $this->requestModel->setContent($this->content);
        }

        // Set start time for measurement of response time
        $startTime = microtime(true);

        // Get request model as XML object (type of SimpleXmlElement)
        $this->requestXmlElement = XmlBuilder::getXmlElement($this->requestModel->toArray());

        //if ($requestModelName == "PaymentRequest") die(var_dump($this->requestModel->toArray(), $this->requestXmlElement, $this->requestXmlElement->asXML()));

        // Get raw XML string
        $this->requestRaw = $this->requestXmlElement->asXML();
        // Initialize communication service and select sandbox mode
        $communicationService = new CommunicationService($this->sandbox);

        // Execute cURL request and get XML response
        $this->responseRaw = $communicationService->send(
            $this->requestRaw,
            $this->connectionTimeout,
            $this->executionTimeout,
            $this->connectionRetries,
            $this->retryDelay
        );

        // Convert XML string to SimpleXmlElement
        $this->responseXmlElement = new \SimpleXMLElement($this->responseRaw);

        // Get validated response object
        $this->response = new ValidateGatewayResponse($this->requestType, $this->responseXmlElement);

        // Save response model
        $this->responseModel = $this->response->getResponseModel();

        // Calculate request response time (in seconds)
        $this->responseTime = microtime(true) - $startTime;
    }

    /**
     * Sets committed subtype and proceeds current request call
     *
     * @param $subtype
     * @return $this
     */
    public function subtype($subtype)
    {
        $subtype = strtolower($subtype);

        if (!$this->requestModel->isSubtypeRequired()) {
            throw new RequestException("" . $this->requestType . " denies Subtype");
        } elseif (!in_array($subtype, $this->requestModel->getAdmittedSubtypes())) {
            throw new RequestException("Subtype '" . $this->requestType . "' is invalid on ". $this->requestType);
        }

        $this->subtype = $subtype;
        $this->callRequest();

        return $this;
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

    /**
     * Returns all available RatePAY request operations
     *
     * @return array
     */
    public function getAvailableRequests($returnAsJson = false)
    {
        $availableRequests = [];
        foreach (ModelMapper::getRequestModels() as $requestModel) {
            if (class_exists(ModelMapper::getFullPathRequestModel($requestModel))) {
                $availableRequests[] = $requestModel;
            }
        }

        return ($returnAsJson) ? json_encode($availableRequests) : $availableRequests;
    }

    /**
     * Returns the response time in seconds
     *
     * @return float
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Returns the raw XML request string
     *
     * @return string
     */
    public function getRequestRaw()
    {
        return $this->requestRaw;
    }

    /**
     * Returns the raw XML response string
     *
     * @return string
     */
    public function getResponseRaw()
    {
        return $this->responseRaw;
    }

    /**
     * Returns the request as SimpleXMLElement
     *
     * @return SimpleXMLElement
     */
    public function getRequestXmlElement()
    {
        return $this->requestXmlElement;
    }

    /**
     * Returns the response as SimpleXMLElement
     *
     * @return SimpleXMLElement
     */
    public function getResponseXmlElement()
    {
        return $this->responseXmlElement;
    }

    /**
     * Clears all class attributes
     */
    private function clearAttributes()
    {
        foreach (get_class_vars(__CLASS__) as $attribute => $value) {
            switch ($attribute) {
                case "sandbox":
                case "connectionTimeout":
                case "executionTimeout":
                case "connectionRetries":
                case "retryDelay":
                    continue;
                    break;
                default:
                    $this->$attribute = null;
            }
        }
    }

}