<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Service;

use RatePAY\Exception\RequestException;

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

        try {
            $this->responseModel = new $responseInstance($response);

            $this->responseModel->validateResponse();
        } catch (\Throwable $exception) {
            // this catch works only on PHP < 7.0. On PHP versions lower than 7.0 the error will be propagated because
            // it is not catchable.
            // This will catch all errors which will throw by accessing non-existing fields or methods on objects
            $message = $exception->getPrevious() ? $exception->getPrevious()->getMessage() : $exception->getMessage();
            throw new RequestException(sprintf('An error occurred during the processing of the response from the gateway. Error message: %s', $message));
        }
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
