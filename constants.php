<?php

const QUIT = 'exit';
const INFO = 'help';
const AGREE = 'yes';
const DEGREE = 'no';
const HISTORY = 'history';
const SYSTEM_COMMANDS = [QUIT, INFO, HISTORY];
const EASY_COMMANDS = ['+', '-', '*', '/'];
const AVAILABLE_COMMANDS = ['+', '-', '*', '/', '^', 'sr', QUIT, INFO, HISTORY];