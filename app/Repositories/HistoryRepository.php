<?php

namespace App\Repositories;

class HistoryRepository extends Repository
{
    public function create($date, $argument1, $argument2, $command, $result)
    {
        $content = $this->all();

        $content[] = [
            'date' => $date,
            'first_operand' => $argument1,
            'second_operand' => $argument2,
            'sign' => $command,
            'result' => $result
        ];

        file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($content));
    }
}
