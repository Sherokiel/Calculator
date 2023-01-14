<?php

namespace Tests;

use App\Services\CalculationService;
use App\Exceptions\CalculateUndefinedCommandsException;

class ServiceTest extends Tests
{
    public function __construct()
    {
        parent::__construct(null, null);

        $this->CalculationService = new CalculationService();
    }

    protected function testCalculate()
    {
        $argument1 = 5;
        $argument2 = 6;
        $command = '+';

        $result = $this->CalculationService->calculate($argument1, $command, $argument2);

        $this->assertEquals(11, $result);
    }

    protected function testCalculateUndefinedCommandsException()
    {
        {
            $this->assertExceptionThrowed(CalculateUndefinedCommandsException::class, 'Undefined command.', function () {
                $this->CalculationService->calculate('1', '>', '2');
            });
        }
    }
}