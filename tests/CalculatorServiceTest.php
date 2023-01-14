<?php

namespace Tests;

use App\Services\CalculatorService;
use App\Exceptions\UndefinedCalculatorCommandException;

class CalculatorServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct(null, null);

        $this->CalculationService = new CalculatorService();
    }

    protected function testCalculate()
    {
        $result = $this->CalculationService->calculate(5, '+', 6);

        $this->assertEquals(11, $result);
    }

    protected function testCalculateUndefinedCommandsException()
    {
        $this->assertExceptionThrowed(UndefinedCalculatorCommandException::class, 'Undefined command.', function () {
            $this->CalculationService->calculate('1', '>', '2');
        });
    }
}