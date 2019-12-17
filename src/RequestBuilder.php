<?php

namespace RatePAY;

use RatePAY\Model\Request\SubModel\Content\ShoppingBasket;
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
     * @var \SimpleXMLElement
     */
    private $requestXmlElement;

    /**
     * Response object
     *
     * @var \SimpleXMLElement
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
     * Gateway URL (optional)
     * If not defined default production or integration URLs will be used.
     *
     * @var int
     */
    private $gatewayUrl = null;

    /**
     * RequestBuilder constructor. Sets sandbox mode
     *
     * @param bool $sandbox
     */
    public function __construct($sandbox = false, $gatewayUrl = null)
    {
        $this->sandbox = !!$sandbox;
        $this->gatewayUrl = empty($gatewayUrl) ? null : $gatewayUrl;
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
     * @throws RequestException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 4) == "call") {
            return $this->callMethod($name, $arguments);
        } elseif (substr($name, 0, 3) == "get" || substr($name, 0, 2) == "is") {
            return $this->getMethod($name, $arguments);
        } else {
            throw new RequestException("Action '" . $name . "' not valid");
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|bool|RequestBuilder
     * @throws RequestException
     */
    private function callMethod($name, $arguments)
    {
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

        /*
         * If Payment Request is called without transaction id,
         * Payment Init will called automatically before sending Payment Request.
         */
        if ($requestModelName == "PaymentRequest" && is_null($this->head->getTransactionId())) {
            $autoPI = $this->callAutomaticPaymentInit();
            // callAutomaticPaymentInit returns true if call was successful. Just on failure it returns PI response model.
            if ($autoPI !== true) {
                return $autoPI;
            }
        }

        /*
         * Call main request
         * If no subtype needed or already set, call request immediately. Otherwise subtype method calls the request.
         */
        if (!$this->requestModel->isSubtypeRequired() || $this->head->isSubtypeSet()) {
            $this->callRequest();
        }

        /*
         * Call automatic Confirmation Deliver if AutoDelivery is set
         */
        if ($requestModelName == "PaymentRequest" && $this->isSuccessful()) {
            $autoCD = $this->callAutomaticConfirmationDeliver();
            // callAutomaticConfirmationDeliver returns true if call is not necessary or was successful. Just on failure it returns CD response model.
            if ($autoCD !== true) {
                return $autoCD;
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws RequestException
     */
    private function getMethod($name, $arguments)
    {
        if (method_exists($this->responseModel, $name)) {
            if (count($arguments) > 0) {
                return $this->responseModel->$name(current($arguments));
            } else {
                return $this->responseModel->$name();
            }
        } else {
            throw new RequestException("Action '" . $name . "' not valid");
        }
    }

    /**
     * Calls requested request and saves results in class attributes
     *
     * @throws Exception\CurlException
     */
    private function callRequest()
    {
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

        // Get raw XML string
        $this->requestRaw = $this->requestXmlElement->asXML();
        // Initialize communication service and select sandbox mode
        $communicationService = new CommunicationService($this->sandbox, $this->gatewayUrl);

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
     * @throws Exception\CurlException
     * @throws RequestException
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
     * @return \SimpleXMLElement
     */
    public function getRequestXmlElement()
    {
        return $this->requestXmlElement;
    }

    /**
     * Returns the response as SimpleXMLElement
     *
     * @return \SimpleXMLElement
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
                case "gatewayUrl":
                    continue 2;
                    break;
                default:
                    $this->$attribute = null;
            }
        }
    }

    /**
     * Calls an automated PaymentInit request
     *
     * @return bool|RequestBuilder
     */
    private function callAutomaticPaymentInit()
    {
        $thisClone = clone $this; // Using clone to keep connection settings
        $paymentInit = $thisClone->callPaymentInit($this->head);

        if (!$paymentInit->isSuccessful()) {
            return $paymentInit;
        }

        $this->head->setTransactionId($paymentInit->getTransactionId());

        return true;
    }


    /**
     * Checks if shopping basket contains auto-deliver items and calls an automated ConfirmationDeliver request
     *
     * @return bool|RequestBuilder
     * @throws Exception\ModelException
     */
    private function callAutomaticConfirmationDeliver()
    {
        $shoppingBasket = $this->content->getShoppingBasket();
        $shoppingItems = $shoppingBasket->getItems()->getItem();

        $autoDelivery = false;
        $deliveryBasket = new ShoppingBasket;
        $deliveryItems = new ShoppingBasket\Items;

        foreach ($shoppingItems as $shoppingItem) {
            if ($shoppingItem->getAutoDelivery() || $shoppingBasket->getAutoDelivery()) {
                $deliveryItems->setItem($shoppingItem);
                $autoDelivery = true;
            }
        }
        if ($autoDelivery) {
            $deliveryBasket->setItems($deliveryItems);
        }

        if (!is_null($shoppingBasket->getDiscount()) && ($shoppingBasket->getDiscount()->getAutoDelivery() || $shoppingBasket->getAutoDelivery())) {
            $deliveryBasket->setDiscount($shoppingBasket->getDiscount());
            $autoDelivery = true;
        }

        if (!is_null($shoppingBasket->getShipping()) && ($shoppingBasket->getShipping()->getAutoDelivery() || $shoppingBasket->getAutoDelivery())) {
            $deliveryBasket->setShipping($shoppingBasket->getShipping());
            $autoDelivery = true;
        }

        if ($autoDelivery) {
            $mbContent = new ModelBuilder('Content');
            $mbContent->setShoppingBasket($deliveryBasket);

            // Commit invoicing block if set
            if (!is_null($this->content->getInvoicing())) {
                $mbContent->setInvoicing($this->content->getInvoicing());
            }

            $thisClone = clone $this; // Using clone to keep connection settings
            $confirmationDeliver = $thisClone->callConfirmationDeliver($this->head, $mbContent);

            if (!$confirmationDeliver->isSuccessful()) {
                return $confirmationDeliver;
            }
        }

        return true;
    }
}
