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