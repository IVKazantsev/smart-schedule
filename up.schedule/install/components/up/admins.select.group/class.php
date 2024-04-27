<?php

use Bitrix\Main\Engine\CurrentUser;

class AdminsSelectGroupComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!$this->checkRole())
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

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
	}
}
