<?php

class CouplesListComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->fetchGroupList();
		$this->includeComponentTemplate();
	}

	protected function fetchGroupList(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		$groups = \Up\Schedule\Model\GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
		$currentGroup = [];
		foreach ($groups as $group)
		{
			if((int)$group['ID'] === $currentGroupId)
			{
				$currentGroup = $group;
			}
		}
		$this->arResult['GROUPS'] = $groups;
		$this->arResult['CURRENT_GROUP_ID'] = $currentGroupId;
		$this->arResult['CURRENT_GROUP'] = $currentGroup;
	}
}