<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\CoupleService;

class CouplesList extends Controller
{
	private array $couples = [];
	public function getCouplesListAction(): array
	{
		$this->fetchCouples();
		return [
			'couples' => $this->couples,
		];
	}

	public function addCoupleAction(): array
	{
		$data = [
			'GROUP_ID' => request()->get('GROUP_ID'),
			'SUBJECT_ID' => request()->get('SUBJECT_ID'),
			'TEACHER_ID' => request()->get('TEACHER_ID'),
			'AUDIENCE_ID' => request()->get('AUDIENCE_ID'),
			'DAY_OF_WEEK' => request()->get('DAY_OF_WEEK'),
			'NUMBER_IN_DAY' => request()->get('NUMBER_IN_DAY'),
		];
		CoupleService::addCouple($data);
		return ['result' => true];
	}

	public function fetchAddCoupleDataAction(): array
	{
		$result = [];
		$currentGroupId = (int)request()->get('id');
		/*$numberOfDay = (int)request()->get('numberOfDay');
		$numberOfCouple = (int)request()->get('numberOfCouple');*/
		$this->fetchCouples();
		$subjects = SubjectRepository::getArrayByGroupId($currentGroupId);
		$idListOfSubjects = array_column($subjects, 'SUBJECTSID');
		foreach ($this->couples as $day)
		{
			foreach ($day as $couple)
			{
				if (($index = array_search($couple['UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID'], $idListOfSubjects, true)) !== false)
				{
					unset($subjects[$index]);
					unset($idListOfSubjects[$index]);
				}
			}
		}

		foreach ($subjects as $subject)
		{
			$result[] = [
				'subject' => $subject,
				'teachers' => UserRepository::getArrayOfTeachersBySubjectId((int)$subject['SUBJECTSID']),
				'audiences' => AudienceRepository::getArrayOfAudiencesBySubjectId((int)$subject['SUBJECTSID']),
				];
		}

		return $result;
	}

	protected function fetchCouples(): void
	{
		$currentGroupId = (int)request()->get('id');
		$couples = CoupleRepository::getArrayByGroupId($currentGroupId);
		/*$this->data['couples'] = $this->sortCouplesByWeekDay($couples);*/
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
