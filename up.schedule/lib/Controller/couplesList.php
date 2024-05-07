<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;
use Up\Schedule\Exception\AddCoupleException;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\EntityService;

class CouplesList extends Controller
{
	private array $couples = [];

	/**
	 * @param array $coupleInfo
	 *
	 * @return void
	 */
	public function preprocessingCoupleInfo(array $coupleInfo): void
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			$this->addError(new Error('you must be an administrator', 'inappropriate_role'));
		}

		if (
			!$coupleInfo['GROUP_ID']
			|| !$coupleInfo['SUBJECT_ID']
			|| !$coupleInfo['TEACHER_ID']
			|| !$coupleInfo['AUDIENCE_ID']
			|| !$coupleInfo['DAY_OF_WEEK']
			|| !$coupleInfo['NUMBER_IN_DAY']
		)
		{
			$this->addError(new Error('all info must be filled', 'not_filled_couple_info'));
		}
	}

	protected function init(): void
	{
		$this->setActionConfig('getCouplesList', [
			'-prefilters' => [
				Authentication::class,
			],
		]);
	}

	public function getCouplesListAction(string $entity, int $id): array
	{
		$this->fetchCouples($entity, $id);

		return [
			'couples' => $this->couples,
		];
	}

	public function deleteCoupleAction(array $coupleInfo): array
	{
		$this->preprocessingCoupleInfo($coupleInfo);

		try
		{
			CoupleRepository::deleteCouple($coupleInfo);

			return ['result' => true];
		}
		catch (Exception)
		{
			return ['result' => false];
		}
	}

	public function addCoupleAction(array $coupleInfo): array
	{
		$this->preprocessingCoupleInfo($coupleInfo);
		$couplesAtThisTime = CoupleRepository::getByDayAndNumber(
			(int)$coupleInfo['DAY_OF_WEEK'],
			(int)$coupleInfo['NUMBER_IN_DAY']
		);
		foreach ($couplesAtThisTime as $couple)
		{
			if ($couple->getAudienceId() === (int)$coupleInfo['AUDIENCE_ID'])
			{
				$this->addError(new Error('the couple in this audience is busy at this time', 'busy_audience'));

				return [
					'result' => false,
					'errors' => "Пара в этой аудитории в это время занята",
				];
			}

			if ($couple->getTeacherId() === (int)$coupleInfo['TEACHER_ID'])
			{
				$this->addError(new Error('the couple with this teacher is busy at this time', 'busy_teacher'));

				return [
					'result' => false,
					'errors' => "Пара с этим преподавателем в это время занята",
				];
			}

			if ($couple->getGroupId() === (int)$coupleInfo['GROUP_ID'])
			{
				$this->addError(new Error('the couple in this group is busy at this time', 'busy_group'));

				return [
					'result' => false,
					'errors' => "Пара у этой группы в это время занята",
				];
			}
		}

		try
		{
			CoupleRepository::addCouple($coupleInfo);
			$result = [
				'result' => true,
			];
		}
		catch (ObjectPropertyException|ArgumentException|SystemException)
		{
			$this->addError(new Error('failed to add a couple', 'failed_to_add_couple'));
			$result = [
				'result' => false,
			];
		}
		catch (AddCoupleException $exception)
		{
			$result = [
				'result' => false,
				'errors' => $exception->getMessage(),
			];
		}

		return $result;
	}

	public function fetchAddCoupleDataAction(string $entity, int $id): array
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			$this->addError(new Error('you must be an administrator', 'inappropriate_role'));
		}

		$result = [];

		$this->fetchCouples($entity, $id);
		$getMethodName = "getArrayBy{$entity}Id";
		$subjects = SubjectRepository::$getMethodName($id);
		if ($entity === 'group')
		{
			$idListOfSubjects = array_column($subjects, 'ID');
			foreach ($this->couples as $day)
			{
				foreach ($day as $couple)
				{
					if (
						($index = array_search($couple['UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID'], $idListOfSubjects, true))
						!== false
					)
					{
						unset($subjects[$index], $idListOfSubjects[$index]);
					}
				}
			}
		}

		foreach ($subjects as $subject)
		{
			$result[] = [
				'subject' => $subject,
				'teachers' => ($entity === 'teacher') ? [UserRepository::getArrayById($id)]
					: UserRepository::getArrayOfTeachersBySubjectId((int)$subject['ID']),
				'audiences' => ($entity === 'audience') ? [AudienceRepository::getArrayById($id)]
					: AudienceRepository::getArrayOfAudiencesBySubjectId((int)$subject['ID']),
				'groups' => ($entity === 'group') ? [GroupRepository::getArrayById($id)]
					: GroupRepository::getArrayOfGroupsBySubjectId((int)$subject['ID']),
			];
		}

		return $result;
	}

	protected function fetchCouples(string $entity, int $id): void
	{
		$getMethodName = "getArrayBy{$entity}Id";
		$couples = CoupleRepository::$getMethodName($id);
		$this->couples = $this->sortCouplesByWeekDay($couples);
	}

	protected function sortCouplesByWeekDay(?array $couples): array
	{
		$sortedCouples = [];
		foreach ($couples as $couple)
		{
			$weekDay = $couple['WEEK_DAY'];
			$numberOfCouple = $couple['COUPLE_NUMBER_IN_DAY'];
			$sortedCouples[$weekDay][$numberOfCouple] = $couple;
		}

		return $sortedCouples;
	}
}
