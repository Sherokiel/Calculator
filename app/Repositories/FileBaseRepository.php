<?php

namespace App\Repositories;

class FileBaseRepository
{
    protected $dirName;
    protected $fileName;

    public function __construct($fileName, $dirName)
    {
        $this->fileName = $fileName;

        if (!is_dir($dirName)) {
            mkdir($dirName);
        }
        $this->filePath = prepare_file_path("{$dirName}/{$this->fileName}");

        fopen($this->filePath, 'a+');
    }
}