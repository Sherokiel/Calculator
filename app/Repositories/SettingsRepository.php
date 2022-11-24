<?php

namespace App\Repositories;

class SettingsRepository extends Repository
{
    public function getSetting($section, $subsection)
    {
        $settings = $this->all();

        if (is_null($settings)) {
            return $settings[$section][$subsection] = null;
        }

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;

        return file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), json_encode($settings));
    }
}
