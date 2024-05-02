<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Service\EntityService;

class AdminsSelectGroupComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}
		$this->arResult['GROUPS'] = self::getGroupInfo();
		$this->includeComponentTemplate();
	}

	private static function getGroupInfo(): ?array
	{
		return \Up\Schedule\Repository\GroupRepository::getAllArray();
	}
}
