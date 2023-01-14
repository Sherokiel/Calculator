<?php
namespace App\Services;

use App\Exceptions\CalculateUndefinedCommandsException;

class CalculationService
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
                return throw new CalculateUndefinedCommandsException();
        }
    }
}
