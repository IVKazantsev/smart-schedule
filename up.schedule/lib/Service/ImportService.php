<?php

namespace Up\Schedule\Service;

use CUser;
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
			return "Не удалось получить данные из таблиц: {$entities['errors']}";
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
		$audiencesTypesSheet = $spreadsheet->getSheetByName('Типы аудиторий');
		$audiencesSheet = $spreadsheet->getSheetByName('Аудитории');
		$subjectsSheet = $spreadsheet->getSheetByName('Предметы');
		$teachersSheet = $spreadsheet->getSheetByName('Преподаватели');
		$groupsSheet = $spreadsheet->getSheetByName('Группы');
		$studentsSheet = $spreadsheet->getSheetByName('Студенты');

		// $couplesSheet = $spreadsheet->getSheetByName('Пары');

		if (
			$audiencesTypesSheet === null
			|| $audiencesSheet === null
			|| $subjectsSheet === null
			|| $teachersSheet === null
			|| $groupsSheet === null
			|| $studentsSheet === null
		)
		{
			return ['errors' => 'Не все параметры заданы'];
		}

		$audiencesTypes = self::preprocessingSheetData($audiencesTypesSheet);
		$audiences = self::preprocessingSheetData($audiencesSheet);
		$subjects = self::preprocessingSheetData($subjectsSheet);
		$teachers = self::preprocessingSheetData($teachersSheet);
		$groups = self::preprocessingSheetData($groupsSheet);
		$students = self::preprocessingSheetData($studentsSheet);

		// $couples = $this->preprocessingSheetData($couplesSheet);

		return [
			'audiencesTypes' => $audiencesTypes,
			'audiences' => $audiences,
			'subjects' => $subjects,
			'teachers' => $teachers,
			'groups' => $groups,
			'students' => $students,

			// 'COUPLES' => $couples,
		];
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