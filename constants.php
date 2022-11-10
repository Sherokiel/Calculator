<?php

const QUIT = 'exit';
const INFO = 'help';
const AGREE = 'yes';
const DEGREE = 'no';
const HISTORY = 'history';
const EXPORTHISTORY = 'export';
const SYSTEM_COMMANDS = [QUIT, INFO, HISTORY, EXPORTHISTORY];
const BASIC_COMMANDS = ['+', '-', '*', '/'];
const AVAILABLE_COMMANDS = [...BASIC_COMMANDS, '^', 'sr', QUIT, INFO, HISTORY, EXPORTHISTORY];