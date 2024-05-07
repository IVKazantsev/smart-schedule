<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class up_schedule extends CModule
{
	public $MODULE_ID = 'up.schedule';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	public $PARTNER_NAME = "Up - Second Team";
	public $PARTNER_URI = "https://up.bitrix.info/2023/module-4/team-2/finalproject";

	public function __construct()
	{
		$arModuleVersion = [];
		include(__DIR__ . '/version.php');

		if (is_array($arModuleVersion) && $arModuleVersion['VERSION'] && $arModuleVersion['VERSION_DATE'])
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_NAME = Loc::getMessage('UP_SCHEDULE_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('UP_SCHEDULE_MODULE_DESCRIPTION');
	}

	public function InstallDB(): void
	{
		global $DB;

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/db/install.sql');
	}

	public function UnInstallDB(): void
	{
		global $DB;

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/db/uninstall.sql');
	}

	public function InstallFiles(): bool
	{
		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/components',
			$_SERVER['DOCUMENT_ROOT'] . '/local/components/',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/templates',
			$_SERVER['DOCUMENT_ROOT'] . '/local/templates/',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/routes',
			$_SERVER['DOCUMENT_ROOT'] . '/local/routes/',
			true,
			true
		);

		return true;
	}

	public function UnInstallFiles(): bool
	{
		\Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/local/components/up');
		\Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/local/templates/schedule');

		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.schedule/install/routes',
			$_SERVER['DOCUMENT_ROOT'] . '/local/routes/',
		);

		return true;
	}

	public function DoInstall(): void
	{
		global $USER, $APPLICATION;

		if (!$USER->isAdmin())
		{
			return;
		}

		$this->InstallDB();
		$this->InstallFiles();
		$this->InstallEvents();

		ModuleManager::registerModule($this->MODULE_ID);

		$APPLICATION->IncludeAdminFile(
			Loc::getMessage('UP_SCHEDULE_INSTALL_TITLE'),
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/step.php'
		);
	}

	public function DoUninstall(): void
	{
		global $USER, $APPLICATION, $step;

		if (!$USER->isAdmin())
		{
			return;
		}

		$step = (int)$step;
		if ($step < 2)
		{
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_SCHEDULE_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep1.php'
			);
		}
		elseif ($step === 2)
		{
			$this->UnInstallDB();
			$this->UninstallFiles();
			$this->UninstallEvents();

			ModuleManager::unRegisterModule($this->MODULE_ID);

			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_SCHEDULE_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep2.php'
			);
		}
	}
}