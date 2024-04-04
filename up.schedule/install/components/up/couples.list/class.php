<?php

use Bitrix\Main\ORM\Objectify\Collection;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;

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
		$currentGroup = GroupRepository::getById($currentGroupId) ?: [];
		$this->arResult['GROUPS'] = GroupRepository::getAll();
		$this->arResult['CURRENT_GROUP_ID'] = $currentGroup['ID'];
		$this->arResult['CURRENT_GROUP'] = $currentGroup;
	}

	protected function fetchCouples(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		$couples = CoupleRepository::getByGroupId($currentGroupId);
		$this->arResult['SORTED_COUPLES'] = $this->sortCouplesByWeekDay($couples);
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
