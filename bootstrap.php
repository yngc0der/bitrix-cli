<?php

const NO_KEEP_STATISTIC = true;
const NOT_CHECK_PERMISSIONS = true;
const NO_AGENT_STATISTIC = 'Y';
const NO_AGENT_CHECK = true;
const STOP_STATISTICS = true;
const BX_NO_ACCELERATOR_RESET = true;
const DisableEventsCheck = true;
const BX_CRONTAB = true;

$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../');
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
@session_destroy();
