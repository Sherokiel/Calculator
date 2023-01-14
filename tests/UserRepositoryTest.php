<?php

namespace Tests;

use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;
use App\Repositories\UserRepository;

class UserRepositoryTest extends Tests
{
    public function __construct()
    {
        parent::__construct('UserRepositoryTest', 'users', '');

        $this->userRepository = new UserRepository();
    }

    public function testCreateCheckResult()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');
        $result = $this->userRepository->create($dataTest);

        $this->assertEquals($dataTest, $result);
    }

    public function testCreateCheckDB()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');

        $this->userRepository->create($dataTest);
        $usersState = $this->getDataSet('users.json');

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


        $result = $this->getDataSet('users.json');
        $this->assertEquals([$this->getJSONFixture('valid_create_data.json')], $result);
    }

    public function testCreateNotAllFields()
    {
        $this->assertExceptionThrowed(CreateWithoutRequiredFieldsException::class, 'One of required fields does not filled.', function () {
            $data = $this->getJSONFixture('not_all_fields_create_data.json');

            $this->userRepository->create($data);
        });
    }

    public function testGroupByInvalidFieldCheckThrowException()
    {
        $this->assertExceptionThrowed(InvalidFieldException::class, 'Field invalidField is not valid.', function () {
            $this->userRepository->allGroupedBy('invalidField');
        });
    }
}