<?php

namespace Tests;

use App\Services\CalculatorService;
use App\Exceptions\UndefinedCalculatorCommandException;

class CalculatorServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();

        $this->calculationService = new CalculatorService();
    }

    protected function testCalculate()
    {
        $result = $this->calculationService->calculate(5, '+', 6);

        $this->assertEquals(11, $result);
    }

    public function testCreateNotAllFieldsRus()
    {
        $this->setLocale('ru');

        $this->assertUndefinedCalculatorCommandExceptionCatched('Нераспознаная команда.');
    }

    public function testCreateNotAllFieldsEng()
    {
        $this->assertUndefinedCalculatorCommandExceptionCatched('Undefined command.');
    }

    protected function assertUndefinedCalculatorCommandExceptionCatched($expectedMessage)
    {
        $this->assertExceptionThrowed(UndefinedCalculatorCommandException::class, $expectedMessage, function () {
            $this->calculationService->calculate('1', '>', '2');
        });
    }
}