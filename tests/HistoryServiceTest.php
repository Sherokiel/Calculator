<?php

namespace Tests;

use App\Services\HistoryService;
use App\Exceptions\HistoryServiceUserNullException;

class HistoryServiceTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function beforeTestsProcessing()
    {
        parent::beforeTestsProcessing();

        $this->historyService = new HistoryService();

    }

    public function testCreateWithoutUser()
    {
        $this->assertExceptionThrowed(HistoryServiceUserNullException::class, 'Field user cant be empty or null.', function () {
            $this->historyService->create('5', '6', '*', '30');
        });
    }
    public function testCreateUserFieldExist()
    {
        $this->historyService->setUser(['user_name' => 'testUser']);

        $result = $this->historyService->create('5', '6', '*', '30');

        $this->assertEquals($this->getJSONFixture('create_user_field_exist.json'), $result);
    }
}