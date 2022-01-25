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
use RatePAY\Service\OfflineInstallmentCalculation;

class OfflineInstallmentCalculationTest extends TestCase
{
    public function testCallOfflineCalculation()
    {
        $this->markTestSkipped('Due to lack of understanding of ModelBuilder functionalities!');
        // it also seems that `callOfflineInstallmentCalculation` is unused at all...
        $service = new OfflineInstallmentCalculation();
        $calculation = $service->callOfflineCalculation(new ModelBuilder());
        $this->assertEquals('today', $calculation);
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
}
