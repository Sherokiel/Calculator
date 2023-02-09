<?php

namespace App\Services;

use App\Repositories\HistoryRepository;
use App\Exporters\HistoryConsoleExporter;
use App\Exporters\HistoryTxtExporter;

class HistoryService
{
    protected $historyRepository;
    protected $historyConsoleExporter;
    protected $historyTxtExporter;
    protected $user;

    protected $env;
    public function __construct()
    {
        $this->env = getenv('APP_ENV');
        $this->historyRepository = new HistoryRepository();
        $this->historyConsoleExporter = new HistoryConsoleExporter();
        $this->historyTxtExporter = new HistoryTxtExporter();
    }

    public function create($argument1, $argument2, $command, $result)
    {
        return $this->historyRepository->create([
            'user_name' => $this->user,
            'date' => $this->now(),
            'first_operand' => $argument1,
            'second_operand' => $argument2,
            'sign' => $command,
            'result' => $result,
        ]);
    }
    public function export($output, $date, $fullPathName)
    {
        $condition = (is_null($date))
            ? []
            : ['date' => $date];

        $condition['user_name'] = $this->user;

        if ($output === EXPORT_HISTORY) {
            $this->historyTxtExporter->setFilePath($fullPathName)->export($condition);
        } else {
            $this->historyConsoleExporter->export($condition);
        }
    }

    public function setUser($user)
    {
        $this->user = $user['username'];
    }

    public function now()
    {
        return ($this->env === 'testing' )
            ? '09.02.2023'
            : now();
    }
}