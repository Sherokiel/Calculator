<?php

class Repository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

        if (!is_dir($this->dirName)) {
            mkdir($this->dirName);
        }

        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
    }
}
