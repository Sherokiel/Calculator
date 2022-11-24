<?php
require "Class.php";

class SettingsRepository extends Repository
{
//    protected $dirName = 'data_storage';
//    protected $fileName;
//
//    public function __construct($fileName)
//    {
//        $this->fileName = $fileName;
//
//        if (!is_dir($this->dirName)) {
//            mkdir($this->dirName);
//        }
//
//        fopen(prepare_file_path("{$this->dirName}/{$this->fileName}"), 'a+');
//    }

    public function all()
    {
        $settings = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        return json_decode($settings, true);
    }

    public function getSetting($section, $subsection)
    {
        $settings = $this->all();

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($settings));
    }
}