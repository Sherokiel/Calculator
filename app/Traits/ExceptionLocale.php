<?php

namespace Traits;

use App\Repositories\SettingsRepository;

trait locale {
    public function getText()
    {
        $settingRepository = new SettingsRepository();
        $lang = $settingRepository->getSetting('localization', 'locale');

        return json_decode(file_get_contents(prepare_file_path("locale/$lang.json")), true);
    }
}