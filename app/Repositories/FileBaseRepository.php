<?php

namespace App\Repositories;

class FileBaseRepository
{
    protected $fileName;
    protected $dirName;
    protected $filePath;

    public function __construct($fileName, $dirName)
    {
        $this->fileName = $fileName;
        $this->dirName = $dirName;
        $this->filePath = prepare_file_path("{$dirName}/{$fileName}");

        if (!is_dir($dirName)) {
            mkdir($dirName);
        }

        fopen($this->filePath, 'a+');
    }
}