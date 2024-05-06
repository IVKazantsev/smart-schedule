<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Up\Schedule\Service\EntityService;

class AdminPanelComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}
		$this->includeComponentTemplate();
	}
}