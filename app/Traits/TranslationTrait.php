<?php

namespace Traits;

use App\Repositories\SettingsRepository;

trait TranslationTrait
{

    protected function getText($typeOfText, $text, $replacements = null)
    {
        $settingRepository = new SettingsRepository();
        $lang = $settingRepository->getSetting('localization', 'locale');
        $messages = json_decode(file_get_contents(prepare_file_path("locale/$lang.json")), true);;
        $message = $messages[$typeOfText][$text];

        if (!is_null($replacements)) {
            foreach ($replacements as $key => $value) {
                $message = str_replace("%{$key}%", $value, $message);
            }
        }

        return $message;
    }
}