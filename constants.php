<?php

const CHOICE_LANGUAGE = 'language';
const QUIT = 'exit';
const INFO = 'help';
const AGREE = 'yes';
const DEGREE = 'no';
const HISTORY = 'history';
const EXPORT_HISTORY = 'export';
const SCREEN = 'screen';
const RUS = 'ru';
const ENG = 'en';
const LANGUAGE = [RUS, ENG];
const SYSTEM_COMMANDS = [QUIT, INFO, HISTORY, CHOICE_LANGUAGE];
const BASIC_COMMANDS = ['+', '-', '*', '/'];
const AVAILABLE_COMMANDS = [...BASIC_COMMANDS, '^', 'sr', ...SYSTEM_COMMANDS, CHOICE_LANGUAGE];
const FULL = 'full';
const HISTORY_COMMANDS = [EXPORT_HISTORY, FULL, 'help', 'back'];

const HISTORY_VIEWER_COMMANDS = [
     FULL => 'Show full history of all time',
     EXPORT_HISTORY => 'Export history in file',
    'Back' => 'Return to main menu',
    'DD-MM-YYYY' => 'Show history by particular date(Example: 21-10-1990)'
];

