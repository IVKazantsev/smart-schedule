<?php

use Bitrix\Main\Context;
use Bitrix\Main\Engine\CurrentUser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_GroupSubject;
use Up\Schedule\Model\EO_GroupSubject_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Repository\AudienceTypeRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Service\ImportService;

class ImportDataComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if (!$this->checkRole())
		{
			LocalRedirect('/404/');
		}

		if(Context::getCurrent()?->getRequest()->isPost())
		{
			$this->processImporting();
		}

		$this->includeComponentTemplate();
	}

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
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
		}
	}
}
