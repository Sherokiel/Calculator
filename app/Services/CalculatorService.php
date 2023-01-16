<?php

namespace App\Services;

use App\Exceptions\UndefinedCalculatorCommandException;
use App\Exceptions\LetterOperandException;
use App\Exceptions\SpaceOperandException;
use App\Exceptions\SeparationZeroOperandException;

class CalculatorService
{
    public function calculate($argument1, $command, $argument2)
    {
        switch ($command) {
            case '+':
                return $argument1 + $argument2;
            case '-':
                return $argument1 - $argument2;
            case '*':
                return $argument1 * $argument2;
            case '/':
                return $argument1 / $argument2;
            case '^':
                return pow($argument1, $argument2);
            case 'sr':
                return pow($argument1, (1 / $argument2));
            default:
                throw new UndefinedCalculatorCommandException();
        }
    }

    public function formatOperand($argument, $command, $isSecondOperand = false)
    {
        $intArgument = str_to_number($argument);

        if ($argument !== $intArgument) {
            throw new LetterOperandException();
        }

        if (strlen($argument) === 0) {
            throw new SpaceOperandException();
        }

        settype($argument, 'integer');

        if ($isSecondOperand) {
            if (($argument === 0) && ($command === '/')) {
                throw new SeparationZeroOperandException();
            }
        }

        return $argument;
    }
}
