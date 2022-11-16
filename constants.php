<?php

const QUIT = 'exit';
const INFO = 'help';
const AGREE = 'yes';
const DEGREE = 'no';
const HISTORY = 'history';
const EXPORT_HISTORY = 'export';
const SYSTEM_COMMANDS = [QUIT, INFO, HISTORY, EXPORT_HISTORY];
const BASIC_COMMANDS = ['+', '-', '*', '/'];
const AVAILABLE_COMMANDS = [...BASIC_COMMANDS, '^', 'sr', ...SYSTEM_COMMANDS];
const INFO_BLOCK = [
    '+' => 'Addition operation',
    '-' => 'Subtraction operation',
    '*' => 'Multiplication operation',
    '/' => 'Division operation',
    '^' => 'Construction number in square',
    'sq' => 'Find root of number',
    EXPORT_HISTORY => 'Export history in file',
    QUIT => 'Exit programm',
    HISTORY => 'Show saved history',
    'Full' => 'Show full history of all time',
    'Date formate' => 'DD-MM-YYYY(Example: 21-10-1990)',
];
