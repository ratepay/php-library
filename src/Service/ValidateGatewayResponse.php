<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
