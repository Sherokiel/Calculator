<?php

namespace App\Repositories;

class IniBaseRepository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName . '.ini';

        if (!is_dir($this->dirName)) {
            mkdir($this->dirName);
        }

        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
    }

    public function all()
    {
        $content = parse_ini_file("{$this->dirName}/{$this->fileName}", true);

        if (is_null($content)) {
            return [];
        }

        return $content;
    }

    public function create($item)
    {
        $contents = $this->all();

        $contents[] = $item;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($contents));
    }
}

