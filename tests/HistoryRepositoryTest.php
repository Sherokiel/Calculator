<?php

namespace Tests;

use App\Exceptions\CreateWithoutRequiredFieldsException;
use App\Exceptions\InvalidFieldException;
use App\Repositories\HistoryRepository;

class HistoryRepositoryTest extends Tests
{
    public function __construct()
    {
        parent::__construct('HistoryRepositoryTest', 'history');

        $this->historyRepository = new HistoryRepository();
    }

    public function testCreateCheckResult()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');
        $result = $this->historyRepository->create($dataTest);

        $this->assertEquals($dataTest, $result);
    }

    public function testCreateCheckDB()
    {
        $dataTest = $this->getJSONFixture('valid_create_data.json');
        $this->historyRepository->create($dataTest);

        $historyState = $this->getDataSet('history.json');
        $dataTest = $this->getJSONFixture('create_success_history_state.json');

        $this->assertEquals($dataTest, $historyState);
    }
/**
 * @CreateExtraFields проверка, удалит ли метод create дополнительное поле.
 */
    public function testCreateExtraFields()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $result = $this->historyRepository->create($dataTest);

        $this->assertEquals($this->getJSONFixture('valid_create_data.json'), $result);
    }

    public function testCreateExtraFieldsBD()
    {
        $dataTest = $this->getJSONFixture('extra_fields_create_data.json');
        $this->historyRepository->create($dataTest);
        $result = $this->getDataSet('history.json');

        $this->assertEquals($this->getJSONFixture('create_success_history_state.json'), $result);
    }
 /**
  * @CreateNotAllFields проверка, выдаст ли нужную ошибку если будут не все указаные поля.
  */
    public function testCreateNotAllFields()
    {
        $this->assertExceptionThrowed(CreateWithoutRequiredFieldsException::class, 'One of required fields does not filled.', function () {
            $data = $this->getJSONFixture('not_all_fields_create_data.json');

            $this->historyRepository->create($data);
        });
    }

    public function testGroupByInvalidFieldCheckThrowException()
    {
        $this->assertExceptionThrowed(InvalidFieldException::class, 'Field invalidField is not valid.', function () {
            $this->historyRepository->allGroupedBy('invalidField');
        });
    }


    public function testGroupedBy()
    {
        $result = $this->historyRepository->allGroupedBy('date');

        $this->assertEquals($this->getJSONFixture('valid_GroupedBy_data.json'), $result);
    }
}