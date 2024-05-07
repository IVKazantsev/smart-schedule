<?php

namespace Up\Schedule\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportService
{
	public static function resetData(string $filePath): string
	{
		// Загрузка файла таблицы
		global $DB;
		$spreadsheet = IOFactory::load($filePath);

		// Получение данных
		$entities = self::getEntitiesFromSpreadsheet($spreadsheet);

		if (isset($entities['errors']))
		{
			return GetMessage('FAILED_TO_RETRIEVE_DATA') . ": {$entities['errors']}";
		}

		$DB->StartTransaction();

		$result = EntityService::clearEntitiesFromDB();
		if($result !== '')
		{
			$DB->Rollback();

			return $result;

		}

		$result = EntityService::addEntitiesToDB($entities);

		if($result !== '')
		{
			$DB->Rollback();

			return $result;
		}
		$DB->Commit();

		return '';
	}

	private static function getEntitiesFromSpreadsheet(Spreadsheet $spreadsheet): array
	{
		$audiencesTypesSheet = $spreadsheet->getSheetByName(GetMessage('AUDIENCE_TYPES_SPREADSHEET'));
		$audiencesSheet = $spreadsheet->getSheetByName(GetMessage('AUDIENCES_SPREADSHEET'));
		$subjectsSheet = $spreadsheet->getSheetByName(GetMessage('SUBJECTS_SPREADSHEET'));
		$teachersSheet = $spreadsheet->getSheetByName(GetMessage('TEACHERS_SPREADSHEET'));
		$groupsSheet = $spreadsheet->getSheetByName(GetMessage('GROUPS_SPREADSHEET'));
		$studentsSheet = $spreadsheet->getSheetByName(GetMessage('STUDENTS_SPREADSHEET'));

		$couplesSheet = $spreadsheet->getSheetByName(GetMessage('COUPLES_SPREADSHEET'));

		if (
			$audiencesTypesSheet === null
			|| $audiencesSheet === null
			|| $subjectsSheet === null
			|| $teachersSheet === null
			|| $groupsSheet === null
			|| $studentsSheet === null
		)
		{
			return ['errors' => GetMessage('NOT_EVERYTHING_IS_SET')];
		}

		$audiencesTypes = self::preprocessingSheetData($audiencesTypesSheet);
		$audiences = self::preprocessingSheetData($audiencesSheet);
		$subjects = self::preprocessingSheetData($subjectsSheet);
		$teachers = self::preprocessingSheetData($teachersSheet);
		$groups = self::preprocessingSheetData($groupsSheet);
		$students = self::preprocessingSheetData($studentsSheet);

		$arrayToReturn = [
			'audiencesTypes' => $audiencesTypes,
			'audiences' => $audiences,
			'subjects' => $subjects,
			'teachers' => $teachers,
			'groups' => $groups,
			'students' => $students,
		];

		if ($couplesSheet !== null)
		{
			$couples = self::preprocessingSheetData($couplesSheet);
			$arrayToReturn['couples'] = $couples;
		}

		return $arrayToReturn;
	}

	private static function preprocessingSheetData(Worksheet $sheet): array
	{
		// Получаем построчно все записи
		$data = $sheet->toArray();
		// Удаляем заголовок
		array_shift($data);

		return $data;
	}
}