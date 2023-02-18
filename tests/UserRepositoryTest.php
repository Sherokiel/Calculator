<?php

namespace Tests;

use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;
use App\Repositories\UserRepository;

class UserRepositoryTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
    }

    public function testCreateCheckResult()
    {
        $data = $this->getJSONFixture('valid_create_data.json');
        $result = $this->userRepository->create($data);

        $this->assertEquals($data, $result);
    }

    public function testCreateCheckDB()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');

        $this->userRepository->create($dataTest);
        $usersState = $this->getDataSet('test_data_storage/users.json');

        $this->assertEquals([$dataTest], $usersState);
    }

    public function testCreateExtraFields()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $result = $this->userRepository->create($dataTest);

        $this->assertEquals($this->getJSONFixture('valid_create_data.json'), $result);
    }

    public function testCreateExtraFieldsBD()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $this->userRepository->create($dataTest);

        $result = $this->getDataSet('test_data_storage/users.json');
        $this->assertEquals([$this->getJSONFixture('valid_create_data.json')], $result);
    }

    public function testCreateNotAllFieldsRus()
    {
        $data = '[localization]' . PHP_EOL . 'locale = ru' . PHP_EOL;

        file_put_contents("{$this->iniDirName}/settings.ini", $data);

        $this->CreateNotAllFields('Одно поле не заполнено.');
    }

    public function testCreateNotAllFieldsEng()
    {
        $this->CreateNotAllFields('One of required fields does not filled.');
    }

    public function CreateNotAllFields($expectedMessage)
    {
        $this->assertExceptionThrowed(CreateWithoutRequiredFieldsException::class, $expectedMessage, function () {
            $data = $this->getJSONFixture('not_all_fields_create_data.json');

            $this->userRepository->create($data);
        });
    }

    public function testGroupByInvalidFieldCheckThrowExceptionEng()
    {
        $this->GroupByInvalidFieldCheckThrowException('Field invalidField is not valid.');
    }

    public function testGroupByInvalidFieldCheckThrowExceptionRus()
    {
        $data = '[localization]' . PHP_EOL . 'locale = ru' . PHP_EOL;

        file_put_contents("{$this->iniDirName}/settings.ini", $data);

        $this->GroupByInvalidFieldCheckThrowException("Неверное поле invalidField.");
    }

    public function GroupByInvalidFieldCheckThrowException($expectedMessage)
    {
        $this->assertExceptionThrowed(InvalidFieldException::class, $expectedMessage, function () {
            $this->userRepository->allGroupedBy('invalidField');
        });
    }
}