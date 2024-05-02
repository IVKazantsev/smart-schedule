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
		$this->arResult['SUBJECTS'] = $this->getSubjectsInfo();
		$this->includeComponentTemplate();
	}

	private function getSubjectsInfo(): ?array
	{
		$id = (int)$this->arParams['GROUP_ID'];
		return \Up\Schedule\Repository\SubjectRepository::getArrayByGroupId($id);
	}
}
