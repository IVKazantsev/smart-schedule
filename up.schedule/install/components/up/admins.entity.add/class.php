<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Service\EntityService;

class AdminsEntityAddComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}

		$entity = $this->getEntityInfo();
		$this->arResult['ENTITY'] = $entity;
		$this->includeComponentTemplate();
	}

	public function getEntityInfo(): ?array
	{
		$entityName = (string)$this->arParams['ENTITY'];
		$this->arResult['ENTITY_NAME'] = $entityName;
		return EntityService::getEntityInfoForAdding($entityName);
	}
}
