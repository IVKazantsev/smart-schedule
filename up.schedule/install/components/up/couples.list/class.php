<?php

use Bitrix\Main\ORM\Objectify\Collection;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class CouplesListComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->fetchGroupList();
		$this->fetchCouples();
		$this->includeComponentTemplate();
	}

	protected function fetchGroupList(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		$currentGroup = GroupRepository::getById($currentGroupId) ? : [];
		$this->arResult['GROUPS'] = GroupRepository::getAll();
		$this->arResult['CURRENT_GROUP_ID'] = $currentGroup['ID'];
		$this->arResult['CURRENT_GROUP'] = $currentGroup;
	}

	protected function fetchCouples(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		$groups = GroupRepository::getAll();
		$audiences = AudienceRepository::getAll();
		$teachers = UserRepository::getAllTeachers();
		$geneticPerson = new \Up\Schedule\AutomaticSchedule\GeneticPerson($groups, $audiences, $teachers);
		// $couples = CoupleRepository::getByGroupId($currentGroupId);
		$this->arResult['SORTED_COUPLES'] = $this->sortCouplesByWeekDay($geneticPerson->couples);
	}

	protected function sortCouplesByWeekDay(?EO_Couple_Collection $couples): array
	{
		$sortedCouples = [];
		foreach ($couples as $couple)
		{
			$weekDay = $couple->getWeekDay();
			$numberOfCouple = $couple->getCoupleNumberInDay();
			$sortedCouples[$weekDay][$numberOfCouple] = $couple;
		}

		return $sortedCouples;
	}
}
