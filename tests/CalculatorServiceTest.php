<?php

namespace Tests;

use App\Services\CalculatorService;
use App\Exceptions\UndefinedCalculatorCommandException;

class CalculatorServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();

        $this->CalculationService = new CalculatorService();
    }

    protected function testCalculate()
    {
        $result = $this->CalculationService->calculate(5, '+', 6);

        $this->assertEquals(11, $result);
    }

    public function testCreateNotAllFieldsRus()
    {
        $data = '[localization]' . PHP_EOL . 'locale = ru' . PHP_EOL;

        file_put_contents("{$this->iniDirName}/settings.ini", $data);

        $this-> CalculateUndefinedCommandsException('Нераспознаная команда.');
    }

    public function testCreateNotAllFieldsEng()
    {
        $this->CalculateUndefinedCommandsException('Undefined command.');
    }

    protected function CalculateUndefinedCommandsException($expectedMessage)
    {
        $this->assertExceptionThrowed(UndefinedCalculatorCommandException::class, $expectedMessage, function () {
            $this->CalculationService->calculate('1', '>', '2');
        });
    }
}