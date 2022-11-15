<?php

class HistoryRepository
{
    protected $DS = DIRECTORY_SEPARATOR;
    protected $savedData;

    public function __construct($dirname)
    {
        $this->dirname = $dirname;

        if(!is_dir($dirname)){
            mkdir("{$this->dirname}");
        }

        fopen("{$this->dirname}{$this->DS}history.json", 'a+');

        $this->savedData = $this->fileGetContent();

        if ($this->savedData === null) {
            $this->savedData = [];
        } else {
            $this->savedData = $this->decodeJson();
        }
    }

    function fileGetContent()
    {
        return $this->savedData = file_get_contents("{$this->dirname}{$this -> DS}history.json");
    }

    function decodeJson()
    {
        return $this->savedData = json_decode($this->savedData, true);
    }

    public function create($date, $argument1, $argument2, $command, $result)
    {
        $this->savedData[] = [
            'date' => $date,
            'first_operand' => $argument1,
            'second_operand' => $argument2,
            'sign' => $command,
            'result' => $result
        ];
    }

    function filePutContent()
    {
        if ($this->savedData !== null) {

            $this->savedData = json_encode($this->savedData);
            file_put_contents("{$this->dirname}{$this -> DS}history.json", $this->savedData);
        }
    }
}
