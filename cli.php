<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\Loader;
use Symfony\Component\Console\Application;
use Yngc0der\Cli\CommandsLoader;

require_once __DIR__ . '/bootstrap.php';

Loader::requireModule('yngc0der.cli');
$application = new Application();
$application->setCommandLoader(new CommandsLoader());
$application->run();
