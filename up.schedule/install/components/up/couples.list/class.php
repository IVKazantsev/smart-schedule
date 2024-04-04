<?php

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\RoleTable;
use Up\Schedule\Repository\GroupRepository;

class CouplesListComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		/*$user = new CUser;
		$arFields = [
			"NAME"              => "Сергей",
			"LAST_NAME"         => "Иванов",
			"EMAIL"             => "ivanov@microsoft.com",
			"LOGIN"             => "ivan1",
			"LID"               => "ru",
			"ACTIVE"            => "Y",
			"PASSWORD"          => "123456",
			"CONFIRM_PASSWORD"  => "123456",
			"UF_ROLE_ID" => 1,
			"UF_GROUP_ID" => 1,
		];
		$ID = $user->Add($arFields);
		if ((int)$ID > 0)
			echo "Пользователь успешно добавлен.";
		else
			echo $user->LAST_ERROR;*/
		$this->fetchGroupList();
		$this->fetchCouples();
		$this->includeComponentTemplate();
	}

	protected function fetchGroupList(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		/*$groups = GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
		$currentGroup = [];
		foreach ($groups as $group)
		{
			if($group->getId() === $currentGroupId)
			{
				$currentGroup = $group;
			}
		}*/
		$currentGroup = GroupRepository::getById($currentGroupId) ?: [];
		$this->arResult['GROUPS'] = GroupRepository::getAll();
		$this->arResult['CURRENT_GROUP_ID'] = $currentGroup['ID'];
		$this->arResult['CURRENT_GROUP'] = $currentGroup;
	}

	protected function fetchCouples(): void
	{
		$currentGroupId = (int)$this->arParams['ID'];
		/*$couples = CoupleTable::query()->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY'])
														  ->where('GROUP_ID', $currentGroupId)->fetchCollection();*/
		$couples = \Up\Schedule\Repository\CoupleRepository::getByGroupId($currentGroupId);
		$this->arResult['SORTED_COUPLES'] = $this->getSortedCouples($couples);
	}

	protected function getSortedCouples(\Bitrix\Main\ORM\Objectify\Collection|null $couples): array
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
