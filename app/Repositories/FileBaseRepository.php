<?php

namespace App\Repositories;

class FileBaseRepository
{
    protected $dirName;
    protected $fileName;
    protected $filePath;

    public function __construct($fileName, $dirName)
    {
        $this->fileName = $fileName;

        $this->filePath = prepare_file_path("{$dirName}/{$fileName}");

        if (!is_dir($dirName)) {
            mkdir($dirName);
        }

        fopen($this->filePath, 'a+');
    }
}