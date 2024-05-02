<?php

namespace Up\Schedule\Service;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\DB\TransactionException;
use Bitrix\Main\Engine\CurrentUser;
use CUser;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_GroupSubject;
use Up\Schedule\Model\EO_GroupSubject_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\EO_SubjectTeacher;
use Up\Schedule\Model\EO_SubjectTeacher_Collection;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\AudienceTypeRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\RoleRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class EntityService
{
	private static array $allowedEntity = [
		'Group',
		'Subject',
		'User',
		'Audience',
		'AudienceType',
	];

	private static array $entitiesToClean = [
		'User',
		'Audience',
		'AudienceType',
		'Group',
		'Subject',
		'Couple',
	];

	private static array $daysOfWeek = [
		1 => 'Понедельник',
		'Вторник',
		'Среда',
		'Четверг',
		'Пятница',
		'Суббота',
		'Воскресенье',
	];

	public static function getEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::getArrayForAdminById($entityId);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function getArrayOfRelatedEntitiesById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::getArrayOfRelatedEntitiesById($entityId);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function deleteEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::deleteById($entityId);
		}
		catch (\Error)
		{
			echo "Entity $entityName not found"; die();
		}
	}
	public static function editEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::editById(
				$entityId,
				self::getData($entityName)
			);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function addEntity(string $entityName)
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::add(
				self::getData($entityName)
			);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not added"; die();
		}
	}

	public static function getEntityInfoForAdding(string $entityName): ?array
	{
		try
		{
			if ($entityName === 'AudienceType')
			{
				return null;
			}
			return self::getEntityRepositoryName($entityName)::getArrayForAdding();
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	private static function getData(string $entityName): ?array
	{
		switch ($entityName)
		{
			case 'group':
				return self::getGroupData();
			case 'audience':
				return self::getAudienceData();
			case 'user':
				return self::getUserData();
			case 'subject':
				return self::getSubjectData();
			case 'audienceType':
				return self::getAudienceTypeData();
			default:
				return null;
		}
	}

	private static function getGroupData(): ?array
	{
		//echo "<pre>";

		return [
			'TITLE' => self::getParameter('TITLE'),
			'SUBJECTS_TO_DELETE' => self::getDeleteSubjectsData(),
			'SUBJECTS_TO_ADD' => self::getAddSubjectsData(),
		];
	}

	private static function getAudienceData(): ?array
	{
		return [
			'NUMBER' => self::getParameter('NUMBER'),
			'TYPE' => self::getParameter('TYPE'),
		];
	}

	private static function getUserData(): ?array
	{
		$data = [
			'NAME' => self::getParameter('NAME'),
			'LAST_NAME' => self::getParameter('LAST_NAME'),
			'EMAIL' => self::getParameter('EMAIL'),
			'LOGIN' => self::getParameter('LOGIN'),
			'PASSWORD' => self::getParameter('PASSWORD'),
			'CONFIRM_PASSWORD' => self::getParameter('CONFIRM_PASSWORD'),
			'ROLE' => self::getParameter('ROLE'),
			'GROUP' => self::getParameter('GROUP'),
		];
		if ($data['ROLE'] === 'Преподаватель')
		{
			$data['SUBJECTS_TO_DELETE'] = self::getDeleteSubjectsData();
			$data['SUBJECTS_TO_ADD'] = self::getAddSubjectsData();
		}
		return $data;
	}

	private static function getDeleteSubjectsData(): ?array
	{
		$subjectsToDelete = [];
		foreach (Context::getCurrent()?->getRequest()->getPostList() as $key => $value)
		{
			//echo $key . "\t\t" . $value . "\n";
			if (str_starts_with($key, 'delete_subject_'))
			{
				$subjectsToDelete[] = (int)substr($key, offset: strlen('delete_subject_'));
			}
		}
		return $subjectsToDelete;
	}

	private static function getAddSubjectsData(): ?array
	{
		$subjectsToAdd = [];
		foreach (Context::getCurrent()?->getRequest()->getPostList() as $key => $value)
		{
			//echo $key . "\t\t" . $value . "\n";
			if (str_starts_with($key, 'add_subject_'))
			{
				$subjectsToAdd[] = (int)$value;
			}
		}
		return $subjectsToAdd;
	}

	private static function getSubjectData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
			'TYPE' => self::getParameter('TYPE'),
		];
	}

	private static function getAudienceTypeData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
		];
	}

	private static function getParameter(string $paramName): ?string
	{
		if (($param = Context::getCurrent()?->getRequest()->get($paramName)) !== '')
		{
			return $param;
		}

		return null;
	}

	public static function getEntityRepositoryName(string $entityName, bool $isNeedPermission = true): ?string
	{
		$entityName = ucfirst(($entityName));
		if($isNeedPermission && !in_array($entityName, self::$allowedEntity, true))
		{
			return null;
		}
		if($entityName === 'Teacher' || $entityName === 'Student')
		{
			$entityName = 'User';
		}

		return '\Up\Schedule\Repository\\' . $entityName . 'Repository';
	}

	public static function addEntitiesToDB(array $entities): string
	{
		foreach ($entities as $key => $entity)
		{
			$methodName = "add{$key}ToDB";
			$result = self::$methodName($entity);
			if($result !== '')
			{
				return $result;
			}
		}

		return '';
	}

	private static function addAudiencesTypesToDB(array $audiencesTypes): string
	{
		$audiencesTypesCollection = new EO_AudienceType_Collection();

		foreach ($audiencesTypes as $audiencesTypeData)
		{
			[$title] = $audiencesTypeData;
			$audienceType = new EO_AudienceType();
			$audienceType->setTitle($title);

			$audiencesTypesCollection->add($audienceType);
		}

		$result = $audiencesTypesCollection->save(true);
		if ($result->isSuccess())
		{
			return '';
		}

		return implode(', ', $result->getErrorMessages());
	}

	private static function addAudiencesToDB(array $audiences): string
	{
		$audiencesCollection = new EO_Audience_Collection();

		foreach ($audiences as $audienceData)
		{
			[$number, $typeTitle] = $audienceData;

			$audience = new EO_Audience();
			$audience->setNumber($number);

			$type = AudienceTypeRepository::getByTitle($typeTitle);
			if ($type === null)
			{
				return "Для аудитории {$number} неверно задан тип аудитории";
			}
			$audience->setAudienceType($type);

			$audiencesCollection->add($audience);
		}

		$result = $audiencesCollection->save(true);
		if ($result->isSuccess())
		{
			return '';
		}

		return implode(', ', $result->getErrorMessages());
	}

	private static function addSubjectsToDB(array $subjects): string
	{
		$subjectsCollection = new EO_Subject_Collection();

		foreach ($subjects as $subjectData)
		{
			[$title, $audienceTypeTitle] = $subjectData;

			$subject = new EO_Subject();
			$subject->setTitle($title);

			$type = AudienceTypeRepository::getByTitle($audienceTypeTitle);
			if ($type === null)
			{
				return "Для предмета {$title} неверно задан тип аудитории";
			}
			$subject->setAudienceType($type);

			$subjectsCollection->add($subject);
		}

		$result = $subjectsCollection->save(true);
		if ($result->isSuccess())
		{
			return '';
		}

		return implode(', ', $result->getErrorMessages());
	}

	private static function addTeachersToDB(array $teachers): string
	{
		foreach ($teachers as $teacher)
		{
			// Деструктурируем массив на поля
			[$name, $lastName, $login, $password, $subjectsString] = $teacher;

			// Сохраняем пользователя
			$user = new CUser();
			$arFields = [
				'NAME' => $name,
				'LAST_NAME' => $lastName,
				'LOGIN' => $login,
				'PASSWORD' => $password,
				'EMAIL' => 'test@test.ru',
				'UF_ROLE_ID' => 2,
			];
			$userId = $user->Add($arFields);
			if ((int)$userId === 0)
			{
				return $user->LAST_ERROR;
			}
			// Преобразуем строку в массив

			$subjects = explode(', ', $subjectsString);

			$subjectTeacherCollection = new EO_SubjectTeacher_Collection();

			foreach ($subjects as $subjectName)
			{
				$subject = SubjectRepository::getByTitle($subjectName);
				if (!$subject)
				{
					return "Для преподавателя {$name} {$lastName} неверно заданы предметы";
				}

				$subjectTeacher = new EO_SubjectTeacher();
				$subjectTeacher->setSubject($subject);
				$subjectTeacher->setTeacherId($userId);
				$subjectTeacherCollection->add($subjectTeacher);
			}

			$result = $subjectTeacherCollection->save(true);

			if (!$result->isSuccess())
			{
				return implode(', ', $result->getErrorMessages());
			}
		}

		return '';
	}

	private static function addGroupsToDB(array $groups): string
	{
		foreach ($groups as $groupData)
		{
			[$title, $subjectsString] = $groupData;

			$group = new EO_Group();

			$group->setTitle($title);

			$result = $group->save(true);
			if(!$result->isSuccess())
			{
				return implode(', ', $result->getErrorMessages());
			}

			$groupId = $result->getId();

			$subjectsWithHours = explode(', ', $subjectsString);

			// Разделим предметы и часы
			$subjectsWithHours = array_map(static function(string $subjectWithHour)
			{
				return explode('/', $subjectWithHour);
			},
				$subjectsWithHours);

			$subjectsTitles = [];
			foreach ($subjectsWithHours as $subjectWithHour)
			{
				$subjectsTitles[] = $subjectWithHour[0];
			}

			$subjects = SubjectRepository::getByTitles($subjectsTitles);

			$groupSubjectCollection = new EO_GroupSubject_Collection();

			$iterator = 0;
			foreach ($subjects as $subject)
			{
				$groupSubject = new EO_GroupSubject();
				$groupSubject->setSubject($subject);
				$groupSubject->setHoursNumber($subjectsWithHours[$iterator][1]);
				$groupSubject->setGroupId($groupId);

				$groupSubjectCollection->add($groupSubject);
				$iterator++;
			}

			$result = $groupSubjectCollection->save(true);
			if (!$result->isSuccess())
			{
				return implode(', ', $result->getErrorMessages());
			}
		}

		return '';
	}

	private static function addStudentsToDB(array $students): string
	{
		foreach ($students as $student)
		{
			// Деструктурируем массив на поля
			[$name, $lastName, $groupTitle, $login, $password] = $student;

			$group = GroupRepository::getByTitle($groupTitle);
			if(!$group)
			{
				return "При создании студента {$name} {$lastName} возникла ошибка: Группы {$groupTitle} не существует";
			}

			// Сохраняем пользователя
			$user = new CUser();
			$arFields = [
				'NAME' => $name,
				'LAST_NAME' => $lastName,
				'LOGIN' => $login,
				'PASSWORD' => $password,
				'EMAIL' => 'test@test.ru',
				'UF_GROUP_ID' => $group->getId(),
				'UF_ROLE_ID' => 3,
			];
			$userId = $user->Add($arFields);
			if ((int)$userId === 0)
			{
				return $user->LAST_ERROR;
			}
		}

		return '';
	}

	public static function addCouplesToDB(array $couples): string
	{
		$couplesCollection = new EO_Couple_Collection();
		foreach ($couples as $couple)
		{
			// Деструктурируем массив на поля
			[$groupTitle, $subjectTitle, $audienceNumber, $teacherName, $teacherLastName, $dayOfWeek, $numberOfCoupleInDay] = $couple;

			$group = GroupRepository::getByTitle($groupTitle);
			if(!$group)
			{
				return "При создании пары на {$dayOfWeek}, {$numberOfCoupleInDay} пару возникла ошибка: Группы {$groupTitle} не существует";
			}

			$subject = SubjectRepository::getByTitle($subjectTitle);
			if(!$subject)
			{
				return "При создании пары на {$dayOfWeek}, {$numberOfCoupleInDay} пару возникла ошибка: Предмета {$subjectTitle} не существует";
			}

			$audience = AudienceRepository::getByNumber($audienceNumber);
			if(!$audience)
			{
				return "При создании пары на {$dayOfWeek}, {$numberOfCoupleInDay} пару возникла ошибка: Аудитории {$audienceNumber} не существует";
			}

			$teacher = UserRepository::getTeacherByFirstAndLastName($teacherName, $teacherLastName);
			if(!$teacher)
			{
				return "При создании пары на {$dayOfWeek}, {$numberOfCoupleInDay} пару возникла ошибка: Преподаватель {$teacherName} {$teacherLastName} не добавлен";
			}

			$couple = new EO_Couple();
			$couple->setGroup($group);
			$couple->setSubject($subject);
			$couple->setAudience($audience);
			$couple->setTeacher($teacher);

			if (is_string($dayOfWeek))
			{
				$dayOfWeek = array_search($dayOfWeek, self::$daysOfWeek, true);
			}

			$couple->setWeekDay($dayOfWeek);
			$couple->setCoupleNumberInDay($numberOfCoupleInDay);

			$couplesCollection->add($couple);
		}

		$result = $couplesCollection->save(true);
		if ($result->isSuccess())
		{
			return '';
		}

		return implode(', ', $result->getErrorMessages());
	}

	public static function clearEntitiesFromDB(): string
	{
		foreach (self::$entitiesToClean as $entity)
		{
			$repository = self::getEntityRepositoryName($entity, false);
			$result = $repository::deleteAllFromDB();
			if($result !== '')
			{
				return $result;
			}
		}

		return '';
	}
}
