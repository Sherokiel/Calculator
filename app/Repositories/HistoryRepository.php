<?php

namespace App\Repositories;

class HistoryRepository extends Repository
{
//    protected $dirName = 'data_storage';
//    protected $fileName;
//
//    public function __construct($fileName)
//    {
//        $this->fileName = $fileName;
//
//        if (!is_dir($this->dirName)) {
//            mkdir($this->dirName);
//        }
//
//        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
//    }

    function all()
    {
        $content = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        if ($content === null) {
            return [];
        }

        return  json_decode($content, true);
    }

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
