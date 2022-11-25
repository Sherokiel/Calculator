<?php

namespace App\Repositories;

class JsonBaseRepository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName . '.json';

        if (!is_dir($this->dirName)) {
            mkdir($this->dirName);
        }

        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
    }

    public function all()
    {
        $content = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        if (is_null($content)) {
            return [];
        }

    return json_decode($content, true);
    }

    public function create($item)
    {
        $contents = $this->all();

        $contents[] = $item;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($contents));
    }
}
