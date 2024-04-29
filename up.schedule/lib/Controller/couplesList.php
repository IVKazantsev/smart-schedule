<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Entity\Query;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\CoupleService;

class CouplesList extends Controller
{
	private array $couples = [];

	protected function init(): void
	{
		$this->setActionConfig('getCouplesList', [
			'-prefilters' => [
				Authentication::class
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
		try {
			CoupleRepository::deleteCouple($coupleInfo);
			return ['result' => true];
		}
		catch (\Exception)
		{
			return ['result' => false];
		}
	}

	public function addCoupleAction(array $coupleInfo): array
	{
		if(!check_bitrix_sessid())
		{
			return [
				'result' => false,
			];
		}

		CoupleRepository::addCouple($coupleInfo);

		return [
			'result' => true,
		];
	}

	public function fetchAddCoupleDataAction(string $entity, int $id): array
	{
		$result = [];
		/*$numberOfDay = (int)request()->get('numberOfDay');
		$numberOfCouple = (int)request()->get('numberOfCouple');*/
		$this->fetchCouples($entity, $id);
		$getMethodName = "getArrayBy{$entity}Id";
		$subjects = SubjectRepository::$getMethodName($id);
		if($entity === 'group')
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
				'teachers' => ($entity === 'teacher') ? [ UserRepository::getArrayById($id) ] : UserRepository::getArrayOfTeachersBySubjectId((int)$subject['ID']),
				'audiences' => ($entity === 'audience') ? [ AudienceRepository::getArrayById($id) ] : AudienceRepository::getArrayOfAudiencesBySubjectId((int)$subject['ID']),
				'groups' => ($entity === 'group') ? [ GroupRepository::getArrayById($id) ] : GroupRepository::getArrayOfGroupsBySubjectId((int)$subject['ID']),
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
