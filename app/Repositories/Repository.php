<?php

namespace App\Repositories;

class JsonBaseRepository
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

    public function all()
    {
        $extention = pathinfo(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        if ($extention['extension'] === 'json') {
            $content = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

            if (is_null($content)) {
                return [];
            }

            return json_decode($content, true);
        }
        $content = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        if ($content === '') {
            return [];
        }

        $content = parse_ini_file("{$this->dirName}/{$this->fileName}", true);

        if (is_null($content)) {
            return [];
        }

        return $content;
    }

    public function create($date)
    {
        $content = $this->all();

        $content[] = $date;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($content));
    }
}
