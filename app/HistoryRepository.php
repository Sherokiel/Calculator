<?php

class HistoryRepository
{
    protected $DS = DIRECTORY_SEPARATOR;
    protected $dirname = 'data_storage';
    protected $fileName = 'history.json';

    public function __construct()
    {
        if (!is_dir($this->dirname)) {
            mkdir($this->dirname);
        }

        fopen("{$this->dirname}{$this->DS}{$this->fileName}", 'a+');
    }

    function all()
    {
        $content = file_get_contents("{$this->dirname}{$this->DS}{$this->fileName}");

        if ($content === null) {
            return [];
        }

        return  json_decode($content, true);
    }

    public function create($argument1, $argument2, $command, $result)
    {
        $content = $this->all();
        $date = date('d-m-Y');
        $content[] = [
            'date' => $date,
            'first_operand' => $argument1,
            'second_operand' => $argument2,
            'sign' => $command,
            'result' => $result
        ];

        file_put_contents("{$this->dirname}{$this->DS}{$this->fileName}", json_encode($content));
    }
}
