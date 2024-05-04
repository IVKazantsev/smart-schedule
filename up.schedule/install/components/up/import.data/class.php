<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Context;
use Up\Schedule\Service\EntityService;
use Up\Schedule\Service\ImportService;

class ImportDataComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}

		if(Context::getCurrent()?->getRequest()->isPost())
		{
			$this->processImporting();
		}

		$this->includeComponentTemplate();
	}

	protected function processImporting(): void
	{
		if(!check_bitrix_sessid())
		{
			$this->arResult['ERRORS'] = 'Сессия истекла';
			return;
		}
		$file = Context::getCurrent()?->getRequest()->getFile('excel-file');

		$error1 = CFile::CheckFile($file, 0, 'application/vnd.ms-excel');
		$error2 = CFile::CheckFile($file, 0, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if($error1 !== '' && $error2 !== '')
		{
			$this->arResult['ERRORS'] = 'uploading error: ' . $error1 . $error2;
			return;
		}

		$fileId = CFile::SaveFile($file, '/up.schedule/uploaded_excel_data');

		if (!$fileId)
		{
			$this->arResult['ERRORS'] = 'Cannot save file';
			return;
		}

		$filePath = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($fileId);
		$errors = ImportService::resetData($filePath);
		if($errors !== '')
		{
			$this->arResult['ERRORS'] = $errors;
			return;
		}

		$this->arResult['SUCCESS'] = GetMessage('SUCCESS_IMPORT');
	}
}
