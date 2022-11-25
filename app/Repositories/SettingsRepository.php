<?php

namespace App\Repositories;

class SettingsRepository extends IniBaseRepository
{
    public function __construct()
    {
        return parent::__construct('settings');
    }

    public function getSetting($section, $subsection)
    {
        $settings = $this->all();

        if ($settings == []) {

            file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), ini_encode($settings));

            return $settings[$section][$subsection] = 'en';
        }

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;

        file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), ini_encode($settings));
    }
}
