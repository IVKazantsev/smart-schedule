<?php

use Bitrix\Main\Engine\CurrentUser;

class AdminPanelComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!$this->checkRole())
		{
			LocalRedirect('/404/');
		}
		$this->includeComponentTemplate();
	}

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
	}
}