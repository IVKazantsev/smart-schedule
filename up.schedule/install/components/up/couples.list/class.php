<?php

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
		$groups = \Up\Schedule\Model\GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
		$currentGroup = [];
		foreach ($groups as $group)
		{
			if($group->getId() === $currentGroupId)
			{
				$currentGroup = $group;
			}
		}
		$this->arResult['GROUPS'] = $groups;
		$this->arResult['CURRENT_GROUP_ID'] = $currentGroupId;
		$this->arResult['CURRENT_GROUP'] = $currentGroup;
	}

	protected function fetchCouples(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		$couples = \Up\Schedule\Model\CoupleTable::query()->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY'])
														  ->where('GROUP_ID', $currentGroupId)->fetchCollection();
		$sortedCouples = [];
		foreach ($couples as $couple)
		{
			$weekDay = $couple->getWeekDay();
			$numberOfCouple = $couple->getCoupleNumberInDay();
			$sortedCouples[$weekDay][$numberOfCouple] = $couple;
		}
		$this->arResult['SORTED_COUPLES'] = $sortedCouples;
	}
}