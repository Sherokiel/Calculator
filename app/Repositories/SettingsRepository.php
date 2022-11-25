<?php

namespace App\Repositories;

class SettingsRepository extends JsonBaseRepository
{
    public function __construct()
    {
        return parent::__construct('settings.ini');
    }

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
