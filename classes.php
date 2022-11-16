<?php

class HistoryRepository
{
    protected $DS = DIRECTORY_SEPARATOR;
    protected $fileName;

    public function __construct($dirname, $fileName)
    {
        $this->dirname = $dirname;
        $this->fileName = $fileName;

        if (!is_dir($dirname)) {
            mkdir($this->dirname);
        }

        fopen("{$this->dirname}{$this->DS}{$this->fileName}", 'a+');
    }

    function all()
    {
        $content = file_get_contents("{$this->dirname}{$this -> DS}{$this->fileName}");

        if ($content === null) {
            return $content;
        }

        $content = json_decode($content, true);

        return $content;
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
        $content = json_encode($content);
        file_put_contents("{$this->dirname}{$this->DS}{$this->fileName}", $content);
    }
}
