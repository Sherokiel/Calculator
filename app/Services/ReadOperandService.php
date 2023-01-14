<?php
namespace App\Services;

use App\Exceptions\LetterOperandException;
use App\Exceptions\IfSpaceOperandException;
use App\Exceptions\SeparateZeroOperandException;

class ReadOperandService
{
    public function readOperandService($argument, $command, $isSecondOperand = false)
    {
        $int1 = str_to_number($argument);
        $isDataValid = ($argument == $int1);

        if (!$isDataValid) {
            throw new LetterOperandException();
        }

        $isDataValid = strlen($argument) > 0;
        if (!$isDataValid) {
            throw new IfSpaceOperandException();
        }

        settype($argument, 'integer');

        if ($isSecondOperand) {
            $isDataValid = ($command !== '/' || $argument !== 0);

            if (!$isDataValid) {
                throw new SeparateZeroOperandException();
            }
        }

        return $isDataValid;
    }
}