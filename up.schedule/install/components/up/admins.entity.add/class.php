<?php

use Bitrix\Main\Context;
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

		if(Context::getCurrent()?->getRequest()->isPost())
		{
			$this->processAdding();
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

	private function processAdding()
	{
		if(!check_bitrix_sessid())
		{
			$this->arResult['ERRORS'] = 'Сессия истекла';
			return;
		}
		$entityName = Context::getCurrent()?->getRequest()->get('entity');
		EntityService::addEntity($entityName);
	}
}
