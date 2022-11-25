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
            $settings = $this->getDefaultSettings();

            file_put_contents($this->filePath, ini_encode($settings));
        }

        return $settings[$section][$subsection];
    }

    public function setSetting($lang, $section, $subsection)
    {
        $settings = $this->all();
        $settings[$section][$subsection] = $lang;

        file_put_contents($this->filePath, ini_encode($settings));
    }

    public function getDefaultSettings()
    {
        return [
            'localization' => [
                'locale' => 'en'
            ]
        ];
    }
}
