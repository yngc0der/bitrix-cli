<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Composer\Script\Event;

class ComposerScripts
{
    public static function postInstall(Event $event)
    {
        $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../../');

        require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
        require_once __DIR__ . '/../install/index.php';

        $module = new yngc0der_cli(true);
        $module->DoInstall(true);

        $event->getIO()->write('Module yngc0der.cli installed');
    }
}
