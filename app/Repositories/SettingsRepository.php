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
            //file_put_contents(prepare_file_path("{$this->dirName}/{$this->fileName}"), "[{$section}]" . "\n" . "{$subsection} = 'en'");
            $settings = parse_ini_file(prepare_file_path("C:/PHP/calculator/app/Default/defaultSettings.ini"), true);

            return $settings[$section][$subsection];
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
