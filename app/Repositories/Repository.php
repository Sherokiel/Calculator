<?php

namespace App\Repositories;

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

    function all()
    {
        $settings = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        if (is_null($settings)) {
            return [];
        }

        return json_decode($settings, true);
    }
}
