<?php

namespace Tests;

use App\Services\HistoryService;

class HistoryServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();

        $this->historyService = new HistoryService();
    }

    public function testCreateWithoutUser()
    {
        $result = $this->historyService->create('5', '6', '*', '30');

        $this->assertEquals($this->getJSONFixture('create_without_user_field.json'), $result);
    }

    public function testCreateUserFieldExist()
    {
        $this->historyService->setUser(['username' => 'testUser']);

        $result = $this->historyService->create('5', '6', '*', '30');

        $this->assertEquals($this->getJSONFixture('create_user_field_exist.json'), $result);
    }
}