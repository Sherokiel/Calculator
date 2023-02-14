<?php

namespace Traits;

use App\Repositories\SettingsRepository;

trait locale {
    public function getText()
    {
        readline('1234');
       //$settingRepository = new SettingsRepository();
        readline('12345');
        //$lang = $settingRepository->getSetting('localization', 'locale');
        $lang = 'en';

        return json_decode(file_get_contents(prepare_file_path("locale/$lang.json")), true);
    }
}