<?php
/**
 * Created by PhpStorm.
 * User: eiriarte-mendez
 * Date: 11.06.18
 * Time: 11:40
 */

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\ModelBuilder;
use RatePAY\Service\DateTime\DateTime;
use RatePAY\Service\OfflineInstallmentCalculation;

class OfflineInstallmentCalculationTest extends TestCase
{
    /**
     * @dataProvider provideOfflineInstalmentCalculations
     */
    public function testCallOfflineCalculation($contentData, $currentNow, $expectedAmount)
    {
        DateTime::withTestingNow(new DateTime($currentNow));

        $modelBuilder = new ModelBuilder('Content');
        $modelBuilder->setArray($contentData);

        $service = new OfflineInstallmentCalculation();

        $instalmentCalculation = $service->callOfflineCalculation($modelBuilder)
            ->subtype('calculation-by-time');

        DateTime::resetTestingNow();

        $this->assertEquals(round($expectedAmount, 2), $instalmentCalculation);
    }

    public function provideOfflineInstalmentCalculations()
    {
        return [
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 2000,
                        'CalculationTime' => [
                            'Month' => 24,
                        ],
                        'PaymentFirstday' => 28,
                        'ServiceCharge' => 3.95,
                        'InterestRate' => 13.6,
                    ],
                ],
                'currentNow' => '2022-01-23',
                'expectedAmount' => 95.3, // Payment API = 95.31,
            ],
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 2000,
                        'CalculationTime' => [
                            'Month' => 24,
                        ],
                        'PaymentFirstday' => 2,
                        'ServiceCharge' => 3.95,
                        'InterestRate' => 10.5,
                    ],
                ],
                'currentNow' => '2022-01-23',
                'expectedAmount' => 92.7, // Payment API = 92.71,
            ],
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 1000,
                        'CalculationTime' => [
                            'Month' => 24,
                        ],
                        'PaymentFirstday' => 2,
                        'ServiceCharge' => 0,
                        'InterestRate' => 13.7,
                    ],
                ],
                'currentNow' => '2022-01-24',
                'expectedAmount' => 47.63, // Payment API = 47.16,
            ],
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 1000,
                        'CalculationTime' => [
                            'Month' => 24,
                        ],
                        'PaymentFirstday' => 2,
                        'ServiceCharge' => 0,
                        'InterestRate' => 13.7,
                    ],
                ],
                'currentNow' => '2022-01-28',
                'expectedAmount' => 47.56, // Payment API = 47.1,
            ],
        ];
    }

    public function testCallZeroPercentOfflineCalculation()
    {
        $content = new ModelBuilder('Content');
        $content->setArray([
            'InstallmentCalculation' => [
                'Amount' => 2000,
                'CalculationTime' => ['Month' => 24],
                'PaymentFirstday' => 2,
                'ServiceCharge' => 0,
                'InterestRate' => 0,
            ]
        ]);

        $service = new OfflineInstallmentCalculation();
        $monthlyInstalment = $service->callOfflineCalculation($content)->subtype('calculation-by-time');

        $this->assertEquals(round(83.33, 2), $monthlyInstalment);
    }

    /**
     * @dataProvider provideOfflineInstalmentCalculationWithZeroRuntime
     */
    public function testCalculationThrowsExceptionsForZeroRuntimes()
    {
        $content = new ModelBuilder('Content');
        $content->setArray([
            'InstallmentCalculation' => [
                'Amount' => 2000,
                'CalculationTime' => ['Month' => 0],
                'PaymentFirstday' => 2,
                'ServiceCharge' => 3.95,
                'InterestRate' => 0,
            ]
        ]);

        $this->expectException('RatePAY\Exception\OfflineInstalmentCalculationException');

        $service = new OfflineInstallmentCalculation();
        $service->callOfflineCalculation($content)->subtype('calculation-by-time');
    }

    public function provideOfflineInstalmentCalculationWithZeroRuntime()
    {
        return [
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 2000,
                        'CalculationTime' => ['Month' => 0],
                        'PaymentFirstday' => 2,
                        'ServiceCharge' => 3.95,
                        'InterestRate' => 0,
                    ]
                ],
            ],
            [
                'contentData' => [
                    'InstallmentCalculation' => [
                        'Amount' => 2000,
                        'CalculationTime' => ['Month' => 0],
                        'PaymentFirstday' => 2,
                        'ServiceCharge' => 3.95,
                        'InterestRate' => 10.7,
                    ]
                ],
            ],
        ];
    }
}
