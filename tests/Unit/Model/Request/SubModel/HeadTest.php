<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Model\Request\SubModel;

use PHPUnit\Framework\TestCase;
use RatePAY\Exception\ModelException;
use RatePAY\Model\Request\SubModel\Constants;
use RatePAY\Model\Request\SubModel\Head;
use RatePAY\Model\Request\SubModel\Head\Credential;

class HeadTest extends TestCase
{
    public function testTransactionIdSet()
    {
        $head = new Head();

        $this->assertFalse($head->isTransactionIdSet());

        $head->setTransactionId('foo-1234567890');

        $this->assertTrue($head->isTransactionIdSet());
    }

    public function testSubtypeSet()
    {
        $head = new Head();

        $this->assertFalse($head->isSubtypeSet());

        $head->setSubtype('foo');

        $this->assertTrue($head->isSubtypeSet());
    }

    public function testThrowErrorIfNoSystemId()
    {
        $head = new Head();

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'SystemId\' is required');

        $array = $head->toArray();
    }

    public function testThrowErrorIfNoOperation()
    {
        $head = new Head();
        $head->setSystemId('test-system');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'Operation\' is required');

        $array = $head->toArray();
    }

    public function testThrowErrorIfNoCredentials()
    {
        $head = new Head();
        $head->setSystemId('test-system');
        $head->setOperation('testing');

        $this->expectException(ModelException::class);
        $this->expectExceptionMessage('Model exception : Field \'Credential\' is required');

        $array = $head->toArray();
    }

    public function testToArray()
    {
        $head = new Head();
        $head->setSystemId('test-system');
        $head->setOperation('testing');
        $head->setCredential((new Credential())->setProfileId('MY_PROFILE')->setSecuritycode('5€CUr|tyK0D3'));

        $array = $head->toArray();

        $expectedArray = [
            'system-id' => ['cdata' => 'test-system'],
            'operation' => ['value' => 'testing'],
            'credential' => [
                'profile-id' => ['value' => 'MY_PROFILE'],
                'securitycode' => ['value' => '5€CUr|tyK0D3'],
            ],
            'meta' => [
                'systems' => [
                    'system' => [
                        'attributes' => [
                            'name' => ['value' => Constants::LIBRARY_SYSTEM_NAME],
                            'version' => ['value' => Constants::LIBRARY_VERSION],
                        ]
                    ],
                    'api-version' => ['value' => Constants::RATEPAY_API_VERSION],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $array);
    }
}
