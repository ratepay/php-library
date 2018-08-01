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
}
