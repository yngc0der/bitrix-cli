<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class yngc0der_cli
 */
class yngc0der_cli extends CModule
{
    /** @var string */
    public $MODULE_ID = 'yngc0der.cli';

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    /**
     * yngc0der_cli constructor.
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('YC_CLI_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('YC_CLI_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('YC_CLI_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('YC_CLI_PARTNER_URI');
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function DoInstall()
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            Loader::requireModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            $this->InstallTasks();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage('YC_CLI_INSTALL_ERROR_NOT_D7'));
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('YC_CLI_INSTALL_TITLE'),
            $this->getPath() . '/install/step.php'
        );
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function DoUninstall()
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        $request = Context::getCurrent()->getRequest();

        if (is_null($request->get('step')) || (int) $request->get('step') === 1) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YC_CLI_UNINSTALL_TITLE'),
                $this->getPath() . '/install/unstep.php'
            );
        }

        if ((int) $request->get('step') === 2) {
            Loader::includeModule($this->MODULE_ID);

            $this->UnInstallDB();
            $this->UnInstallEvents();
            $this->UnInstallFiles();
            $this->UnInstallTasks();

            Loader::clearModuleCache($this->MODULE_ID);
            ModuleManager::unRegisterModule($this->MODULE_ID);
        }
    }

    /**
     * @return void
     */
    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/tools',
            Application::getDocumentRoot() . '/bitrix/tools',
            true,
            true
        );
    }

    /**
     * @return void
     */
    public function UnInstallFiles()
    {
        DeleteDirFiles(
            __DIR__ . '/tools',
            Application::getDocumentRoot() . '/bitrix/tools'
        );
    }

    /**
     * @return bool
     */
    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    /**
     * @param bool $documentRoot
     * @return string
     */
    public function getPath($documentRoot = true)
    {
        return $documentRoot
            ? dirname(__DIR__)
            : str_ireplace($_SERVER['DOCUMENT_ROOT'],'', dirname(__DIR__));
    }
}
