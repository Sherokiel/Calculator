<?php

class SettingsRepository
{
    protected $dirName = 'settings';
    protected $fileName = 'settings.JSON';

    public function getFullSetting()
    {
        $settings = file_get_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"));

        return json_decode($settings, true);
    }

    public function getSettings($section, $subsection)
    {
        $settings = $this->getFullSetting();

        return $settings[$section][$subsection];
    }

    public function setSettings($lang, $section, $subsection)
    {
        $settings = $this->getFullSetting();
        $settings[$section][$subsection] = $lang;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($settings));
    }
}