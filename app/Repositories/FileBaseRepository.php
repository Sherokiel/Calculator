<?php

namespace App\Repositories;

class FileBaseRepository
{
    protected $dirName = 'data_storage';

    public function __construct($fileName, $dirName = 'data_storage')
    {
        $this->fileName = $fileName;

        if (!is_dir($this->dirName)) {
            mkdir($this->dirName);
        }

        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
    }
}