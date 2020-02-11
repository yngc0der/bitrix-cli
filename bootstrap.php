<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\Loader;
use Yngc0der\Cli\BitrixCommandLoader;
use Symfony\Component\Console\Application;

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('STOP_STATISTICS', true);
define('BX_NO_ACCELERATOR_RESET', true);
define('DisableEventsCheck', true);
define('BX_CRONTAB', true);

$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../');
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
@session_destroy();

Loader::includeModule('yngc0der.cli');

$application = new Application();
$application->setCommandLoader(new BitrixCommandLoader());
$application->run();
