<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Service\EntityService;

class AdminPanelComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}
		$this->includeComponentTemplate();
	}
}