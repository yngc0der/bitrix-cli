<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;

class yngc0der_cli extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_ID = 'yngc0der.cli';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $arModuleInfo = [];
        include __DIR__ . '/info.php';

        $this->MODULE_NAME = $arModuleInfo['MODULE_NAME'];
        $this->MODULE_DESCRIPTION = $arModuleInfo['MODULE_DESCRIPTION'];
        $this->PARTNER_NAME = $arModuleInfo['PARTNER_NAME'];
        $this->PARTNER_URI = $arModuleInfo['PARTNER_URI'];
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function DoInstall(): void
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);
        Loader::requireModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        $this->InstallTasks();

        $APPLICATION->IncludeAdminFile(
            'Module install',
            $this->getPath() . '/install/step.php'
        );
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function DoUninstall(): void
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        $request = Context::getCurrent()->getRequest();

        if (is_null($request->get('step')) || (int)$request->get('step') === 1) {
            $APPLICATION->IncludeAdminFile(
                'Module uninstall',
                $this->getPath() . '/install/unstep.php'
            );
        }

        if ((int)$request->get('step') === 2) {
            Loader::includeModule($this->MODULE_ID);

            $this->UnInstallDB();
            $this->UnInstallEvents();
            $this->UnInstallFiles();
            $this->UnInstallTasks();

            Loader::clearModuleCache($this->MODULE_ID);
            ModuleManager::unRegisterModule($this->MODULE_ID);
        }
    }

    public function InstallFiles(): void
    {
        CopyDirFiles(
            __DIR__ . '/tools',
            Application::getDocumentRoot() . '/bitrix/tools',
            true,
            true
        );
    }

    public function UnInstallFiles(): void
    {
        DeleteDirFiles(
            __DIR__ . '/tools',
            Application::getDocumentRoot() . '/bitrix/tools'
        );
    }

    public function getPath(bool $withDocumentRoot = true): string
    {
        return $withDocumentRoot
            ? dirname(__DIR__)
            : str_ireplace(Application::getDocumentRoot(),'', dirname(__DIR__));
    }
}
