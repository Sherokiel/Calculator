<?php

namespace Traits;

use App\Repositories\SettingsRepository;

trait locale {
    public function getLocaleText()
    {
        $settingRepository = new SettingsRepository();
        $lang = $settingRepository->getSetting('localization', 'locale');

        return json_decode(file_get_contents(prepare_file_path("locale/$lang.json")), true);
    }

    protected function getText($typeOfText, $text, $replacements)
    {
        $message = $this->getLocaleText();
        $message = $message[$typeOfText][$text];

        foreach ($replacements as $key => $value) {
            $message = str_replace("%{$key}%", $value, $message);
        }

        return $message;
    }
}