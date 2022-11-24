<?php

namespace App\Repositories;

class SettingsRepository extends Repository
{
    public function getSetting($section, $subsection)
    {
        $settings = $this->all();

        if ($settings == []) {
            return $settings[$section][$subsection] = null;
        }

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;

        file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), '');

        foreach ($settings as $setting => $settingsItems) {
            file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), "[{$setting}]" . "\n" , FILE_APPEND);
            foreach ($settingsItems as $settingsKey => $settingsItem) {
                file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), "{$settingsKey} = '{$settingsItem}'" . "\n", FILE_APPEND);
            }
        }
    }
}
