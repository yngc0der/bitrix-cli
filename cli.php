<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\Loader;
use Yngc0der\Cli\Application;

require_once __DIR__ . '/bootstrap.php';

Loader::requireModule('yngc0der.cli');
$application = new Application();
$application->run();
