<?php

namespace Tests;

use App\Services\HistoryService;
use App\Exceptions\CreateHistoryEmptyUserException;

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

    public function testCreateUserFieldExist()
    {
        $this->historyService->setUser(['user_name' => 'testUser']);

        $result = $this->historyService->create('5', '6', '*', '30');

        $this->assertEquals($this->getJSONFixture('create_user_field_exist.json'), $result);
    }

    public function testCreateWithoutUser()
    {
        readline('До вот этого момента доходит');
        $this->assertExceptionThrowed(CreateHistoryEmptyUserException::class, 'History can not been created without auth user.', function () {
            $this->historyService->create('5', '6', '*', '30');
        });
    }
}