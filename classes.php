<?php

class HistoryRepository
{
    protected $DS = DIRECTORY_SEPARATOR;
    protected $savedData;
    protected $fileName;

    public function __construct($dirname, $fileName)
    {
        $this->dirname = $dirname;
        $this->fileName = $fileName;

        if(!is_dir($dirname)) {
            mkdir("{$this->dirname}");
        }

        fopen("{$this->dirname}{$this->DS}{$this->fileName}", 'a+');
    }

    function fileGetContent()
    {
        $zaebalsya = file_get_contents("{$this->dirname}{$this -> DS}{$this->fileName}");

        if ($zaebalsya === null) {

            return $zaebalsya;
        }

        $zaebalsya = json_decode($zaebalsya, true);

        return $zaebalsya;
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
            file_put_contents("{$this->dirname}{$this->DS}{$this->fileName}", $this->savedData);
        }
    }

}
