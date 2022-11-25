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
            file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), "[{$section}]" . "\n" . "{$subsection} = 'en'");

            return $settings[$section][$subsection] = 'en';
        }

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;
        $personalSettigns = null;

        foreach ($settings as $setting => $settingsItems) {
            $personalSettigns = $personalSettigns . "[{$setting}]" . "\n";
            foreach ($settingsItems as $settingsKey => $settingsItem) {
                $personalSettigns = $personalSettigns . "{$settingsKey} = '{$settingsItem}'" . "\n";
            }
        }
        file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), $personalSettigns);
    }
}
