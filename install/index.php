<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\LoaderException;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class yngc0der_cli extends \CModule
{
    protected $isCliMode;

	public function __construct($cli = false)
	{
		$arModuleVersion = [];
		include __DIR__ . '/version.php';

		$this->MODULE_ID = 'yngc0der.cli';
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = Loc::getMessage('YC_CLI_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('YC_CLI_MODULE_DESC');

		$this->PARTNER_NAME = Loc::getMessage('YC_CLI_PARTNER_NAME');
		$this->PARTNER_URI = Loc::getMessage('YC_CLI_PARTNER_URI');

		$this->isCliMode = $cli;
	}

    public function DoInstall()
	{
		/** @global \CMain $APPLICATION */
		global $APPLICATION;

		if (!$this->isVersionD7()) {
            throw new LoaderException(Loc::getMessage('YC_CLI_INSTALL_ERROR_NOT_D7'));
		}

        ModuleManager::registerModule($this->MODULE_ID);
		$this->InstallFiles();

		if (!$this->isCliMode) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YC_CLI_INSTALL_TITLE'),
                $this->getPath() . '/install/step.php'
            );
        }
	}

    public function DoUninstall()
	{
		/** @global \CMain $APPLICATION */
		global $APPLICATION;

		$this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);

        if (!$this->isCliMode) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YC_CLI_UNINSTALL_TITLE'),
                $this->getPath() . '/install/unstep.php'
            );
        }
	}

	public function InstallFiles()
    {
        $fileContent = <<<PHP
#!/usr/bin/php
<?php
\$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../');
require_once \$_SERVER['DOCUMENT_ROOT'] . '{$this->getPath(false)}/bootstrap.php';

PHP;

        file_put_contents($this->getCliScriptFilePath(), $fileContent);
    }

    public function UnInstallFiles()
    {
        @unlink($this->getCliScriptFilePath());
    }

    public function getCliScriptFilePath()
    {
        return realpath($this->getPath() . '/../../') . '/cli';
    }

    public function isVersionD7()
	{
		return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
	}

    public function getPath($documentRoot = true)
    {
        return $documentRoot
            ? dirname(__DIR__)
            : str_ireplace($_SERVER['DOCUMENT_ROOT'],'', dirname(__DIR__));
    }
}
