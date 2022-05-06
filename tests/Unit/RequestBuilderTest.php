<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit;

use donatj\MockWebServer\ResponseStack;
use RatePAY\Exception\RequestException;
use RatePAY\ModelBuilder;
use RatePAY\RequestBuilder;
use RatePAY\Tests\Support\GatewayResponses;
use RatePAY\Tests\Support\IntegrationTestCase;

/**
 * @requires PHPUnit 7.5
 */
class RequestBuilderTest extends IntegrationTestCase
{
    use GatewayResponses;

    /** @dataProvider provideUnknownMethods */
    public function testThrowErrorOnUnkownMethod($methodName)
    {
        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Action '{$methodName}' not valid");

        $response = $builder->{$methodName}(null);
    }

    public function provideUnknownMethods()
    {
        return [
            ['getSomeValues'],
            ['isItColdNow'],
        ];
    }

    public function testThrowErrorOnUnkownAction()
    {
        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Action \'makeMeACoffee\' not valid');

        $response = $builder->makeMeACoffee(null);
    }

    public function testThrowErrorOnUnkownOperation()
    {
        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('operation \'PaymentSuccess\' not valid');

        $response = $builder->callPaymentSuccess(null);
    }

    public function testThrowErrorIfRequiredHeadIsMissing()
    {
        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder('Content');

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Request exception : PaymentInit requires Head model');

        $response = $builder->callPaymentInit($head);
    }

    public function testThrowErrorIfRequiredContentIsMissing()
    {
        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ]
        ]);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Request exception : PaymentChange requires Content model');

        $response = $builder->callPaymentChange($head, new class {
        });
    }

    public function testCallPaymentInit()
    {
        self::$gateway->setResponseOfPath(
            '/',
            $this->getPaymentInitResponse()
        );

        $head = new ModelBuilder('Head'); // If no parameter is set, 'head' model will be set by default
        $head->setArray([
            'SystemId' => "Tetris",
            'Credential' => [
                'ProfileId' => 'UMBRELLA_CORP',
                'Securitycode' => 'PASS123456'
            ]
        ]);

        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());
        $response = $builder->callPaymentInit($head);

        $this->assertTrue($response->isSuccessful());
    }

    public function testCallPaymentRequest()
    {
        $mockResponse = $this->getPaymentRequestResponse([
            'transaction-id' => '00-1234567890'
        ]);
        self::$gateway->setResponseOfPath('/', $mockResponse);

        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ],
            'TransactionId' => '00-1234567890',
        ]);

        $shoppingBasket = [
            'ShoppingBasket' => [
                'Items' => [
                    [
                        'Item' => [
                            'ArticleNumber' => 'foo-123',
                            'Description' => 'Foo Adidas 44 1/2 (EU)',
                            'Quantity' => 2,
                            'UnitPriceGross' => 9.99,
                            'TaxRate' => 19,
                        ],
                    ],
                ],
            ]
        ];

        $content = new ModelBuilder('Content');
        $content->setArray($shoppingBasket);

        $response = $builder->callPaymentRequest($head, $content);

        $this->assertTrue($response->isSuccessful());
    }

    public function testCallPaymentRequestWithAutoPaymentInit()
    {
        $paymentInitResponse = $this->getPaymentInitResponse();
        $paymentRequestResponse = $this->getPaymentRequestResponse(['transaction-id' => '00-1234567890']);
        self::$gateway->setResponseOfPath('/', new ResponseStack($paymentInitResponse, $paymentRequestResponse));

        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ]
        ]);

        $content = new ModelBuilder('Content');
        $content->setArray([
            'ShoppingBasket' => [
                'Items' => [
                    [
                        'Item' => [
                            'ArticleNumber' => 'foo-123',
                            'Description' => 'Foo Adidas 44 1/2 (EU)',
                            'Quantity' => 2,
                            'UnitPriceGross' => 9.99,
                            'TaxRate' => 19,
                        ],
                    ],
                ],
            ]
        ]);

        $response = $builder->callPaymentRequest($head, $content);

        $this->assertTrue($response->isSuccessful());
    }

    public function testCallPaymentRequestWithAutoDelivery()
    {
        $paymentRequestResponse = $this->getPaymentRequestResponse(['transaction-id' => '00-1234567890']);
        $autoDeliveryResponse = $this->getAutoConfirmationDeliveryResponse(['transaction-id' => '00-1234567890']);
        self::$gateway->setResponseOfPath('/', new ResponseStack($paymentRequestResponse, $autoDeliveryResponse));

        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ],
            'TransactionId' => '00-1234567890',
        ]);

        $content = new ModelBuilder('Content');
        $content->setArray([
            'ShoppingBasket' => [
                'Items' => [
                    [
                        'Item' => [
                            'ArticleNumber' => 'foo-123',
                            'Description' => 'Foo Adidas 44 1/2 (EU)',
                            'Quantity' => 2,
                            'UnitPriceGross' => 9.99,
                            'TaxRate' => 19,
                        ],
                    ],
                ],
            ]
        ]);
        $content->getModel()->getShoppingBasket()->setAutoDelivery(true);

        $response = $builder->callPaymentRequest($head, $content);

        $this->assertTrue($response->isSuccessful());
    }

    public function testCallPaymentChangeCredit()
    {
        $mockResponse = $this->getPaymentChangeResponse([
            'transaction-id' => '00-1234567890'
        ]);
        self::$gateway->setResponseOfPath('/', $mockResponse);

        $builder = new RequestBuilder(true, self::$gateway->getServerRoot());

        $head = new ModelBuilder();
        $head->setArray([
            'SystemId' => 'SuperSystem',
            'Credential' => [
                'ProfileId' => 'MY_AMAZING_PROFILE',
                'Securitycode' => 'super-secure-code',
            ],
            'TransactionId' => '00-1234567890',
        ]);

        $shoppingBasket = [
            'ShoppingBasket' => [
                'Discount' => [
                    'Description' => "Goodwill refund",
                    'UnitPriceGross' => 20,
                    'TaxRate' => 19,
                ]
            ]
        ];

        $content = new ModelBuilder('Content');
        $content->setArray($shoppingBasket);

        $response = $builder->callPaymentChange($head, $content)->subtype('credit');

        $this->assertTrue($response->isSuccessful());
    }
}
