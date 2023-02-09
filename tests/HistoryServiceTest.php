<?php

namespace Tests;

use App\Repositories\HistoryRepository;
use App\Exporters\HistoryConsoleExporter;
use App\Exporters\HistoryTxtExporter;
use App\Services\HistoryService;

class HistoryServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
        readline('11');
        $this->historyService = new HistoryService();
    }

    public function testCreateUserFieldExist()
    {
        $this->historyService->setUser('TestUser');

        $result = $this->historyService->create('5', '6', '*', '30',);

        $this->assertEquals($this->getJSONFixture('create_user_field_exist.json'), $result);
    }
}