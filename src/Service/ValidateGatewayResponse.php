<?php

namespace RatePAY\Service;


/**
 * Class ValidateGatewayResponse
 * @package Service
 */
class ValidateGatewayResponse
{

    /**
     * Response/request type
     *
     * @var string
     */
    private $responseType;

    /**
     * Response model
     *
     * @var mixed
     */
    private $responseModel;


    /**
     * ValidateGatewayResponse constructor.
     *
     * @param $requestType
     * @param $response
     */
    public function __construct($requestType, \SimpleXMLElement $response)
    {
        $this->responseType = $requestType;
        $responsePath = "RatePAY\\Model\\Response\\";
        $responseInstance = $responsePath . $this->responseType;

        $this->responseModel = new $responseInstance($response);

        $this->responseModel->validateResponse();
    }

    /**
     * Returns response model instance
     *
     * @return mixed
     */
    public function getResponseModel()
    {
        return $this->responseModel;
    }

}