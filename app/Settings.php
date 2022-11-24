<?php

class Settings
{
    protected $DS = DIRECTORY_SEPARATOR;
    protected $dirName = 'settings';
    protected $fileName = 'settings.JSON';

    function get_settings($section, $subsection)
    {
        $settings = file_get_contents("{$this->dirName}{$this->DS}{$this->fileName}");
        $settings = json_decode($settings, true);

        return $settings[$section][$subsection];
    }

    function save_settings($lang, $section, $subsection)
    {
        $settings[$section][$subsection] = $lang;

        return file_put_contents("{$this->dirName}{$this->DS}{$this->fileName}", json_encode($settings));
    }
}


