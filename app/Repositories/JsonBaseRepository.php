<?php

namespace App\Repositories;

class JsonBaseRepository extends FileBaseRepository
{
    protected $dirName = 'data_storage';
    protected $fileName;

    public function __construct()
    {
        return parent::__construct('history', '.json');
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
