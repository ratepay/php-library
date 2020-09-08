<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * RatePAY GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by RatePAY GmbH.
 * All rights reserved by RatePAY GmbH.
 *
 * Copyright (c) 2019 RatePAY GmbH / Berlin / Germany
 */

namespace RatePAY\Service;

/**
 * Class ValidateGatewayResponse.
 */
class ValidateGatewayResponse
{
    /**
     * Response/request type.
     *
     * @var string
     */
    private $responseType;

    /**
     * Response model.
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
        $responsePath = 'RatePAY\\Model\\Response\\';
        $responseInstance = $responsePath . $this->responseType;

        $this->responseModel = new $responseInstance($response);

        $this->responseModel->validateResponse();
    }

    /**
     * Returns response model instance.
     *
     * @return mixed
     */
    public function getResponseModel()
    {
        return $this->responseModel;
    }
}
