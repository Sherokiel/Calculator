<?php

namespace App\Repositories;

class FileBaseRepository
{
    public function __construct($fileName, $extend)
    {
        $this->fileName = $fileName . $extend;

        if (!is_dir($this->dirName)) {
            mkdir($this->dirName);
        }

        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
    }
}