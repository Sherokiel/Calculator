<?php

namespace App\Repositories;

class FileBaseRepository
{
    protected $dirName;

    public function __construct($fileName, $dirName)
    {
        $this->fileName = $fileName;

        if (!is_dir($dirName)) {
            mkdir($dirName);
        }

        fopen(prepare_file_path("{$dirName}/{$this->fileName}"), 'a+');
    }
}